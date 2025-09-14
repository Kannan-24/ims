<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ims\Invoice;
use App\Models\ims\InvoiceItem;
use App\Models\ims\Stock;
use App\Models\ims\Customer;
use App\Models\ims\Service;
use App\Models\ims\ContactPerson;
use App\Models\ims\Product;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'customer.contactPersons']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->from) {
            $query->whereDate('invoice_date', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('invoice_date', '<=', $request->to);
        }

        $invoices = $query->latest()->get();

        return view('ims.invoices.index', compact('invoices'));
    }



    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('stock')->get();
        $services = Service::all();
        return view('ims.invoices.create', compact('customers', 'products', 'services'));
    }

    /**
     * Store a newly created invoice in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required|exists:customers,id',
            'contact_person' => 'required|exists:contact_persons,id',
            'invoice_date' => 'required|date',
            'order_date' => 'required|date',
            'order_no' => 'required|string',
            'order_no_text' => 'nullable|string',
            'terms_condition' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.quantity' => 'required_with:products.*.product_id|integer|min:1',
            'products.*.unit_price' => 'required_with:products.*.product_id|numeric|min:0',
            'products.*.cgst' => 'nullable|numeric|min:0',
            'products.*.sgst' => 'nullable|numeric|min:0',
            'products.*.igst' => 'nullable|numeric|min:0',
            'products.*.total' => 'nullable|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'nullable|exists:services,id',
            'services.*.quantity' => 'required_with:services.*.service_id|integer|min:1',
            'services.*.unit_price' => 'required_with:services.*.service_id|numeric|min:0',
            'services.*.gst_total' => 'nullable|numeric|min:0',
            'services.*.total' => 'nullable|numeric|min:0',
        ]);

        $orderNo = $request->order_no === 'other' ? $request->order_no_text : $request->order_no;

        // Generate the invoice number automatically
        $currentYear = date('Y');
        $nextYear = date('y', strtotime('+1 year'));
        $previousYear = date('y', strtotime('-1 year'));
        $financialYear = (date('m') >= 4) ? $currentYear . '-' . $nextYear : $previousYear . '-' . date('y');

        $lastInvoice = Invoice::where('invoice_no', 'like', 'INV/' . $financialYear . '/%')->latest('id')->first();
        $lastInvoiceNumber = $lastInvoice ? (int)explode('/', $lastInvoice->invoice_no)[2] : 0;
        $newInvoiceNo = 'INV/' . $financialYear . '/' . ($lastInvoiceNumber + 1);

        $totalServiceGst = 0;

        // Calculate total GST from services
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['gst_total'])) {
                    $totalServiceGst += $service['gst_total'];
                }
            }
        }

        foreach ($request->products as $product) {
            $stock = Stock::where('product_id', $product['product_id'])->get();

            if ($stock->isEmpty() || $stock->sum('quantity') - $stock->sum('sold') < $product['quantity']) {
                $productName = Product::find($product['product_id'])->name ?? 'Unknown Product';
                return redirect()->back()->withErrors([
                    'products' => "Product '{$productName}' is out of stock."
                ])->withInput();
            }
        }

        // Calculate totals from products and services
        $totalSubtotal = 0;
        $totalCgst = 0;
        $totalSgst = 0;
        $totalIgst = 0;
        
        // Calculate product totals
        if ($request->has('products')) {
            foreach ($request->products as $product) {
                if (isset($product['product_id'])) {
                    $productModel = Product::findOrFail($product['product_id']);
                    $quantity = (int)$product['quantity'];
                    $unitPrice = (float)str_replace(',', '', $product['unit_price']);
                    $subtotal = $quantity * $unitPrice;
                    $totalSubtotal += $subtotal;
                    
                    $gstPercentage = $productModel->gst_percentage;
                    $isIgst = $productModel->is_igst;
                    
                    if ($isIgst) {
                        $totalIgst += ($subtotal * $gstPercentage) / 100;
                    } else {
                        $totalCgst += ($subtotal * $gstPercentage) / 200;
                        $totalSgst += ($subtotal * $gstPercentage) / 200;
                    }
                }
            }
        }
        
        // Calculate service totals
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['service_id'])) {
                    $quantity = (int)$service['quantity'];
                    $unitPrice = (float)str_replace(',', '', $service['unit_price']);
                    $subtotal = $quantity * $unitPrice;
                    $totalSubtotal += $subtotal;
                    
                    $gstTotal = (float)str_replace(',', '', $service['gst_total'] ?? 0);
                    $totalCgst += $gstTotal / 2;
                    $totalSgst += $gstTotal / 2;
                }
            }
        }
        
        $grandTotal = $totalSubtotal + $totalCgst + $totalSgst + $totalIgst;

        $invoice = Invoice::create([
            'customer_id' => $request->customer,
            'contactperson_id' => $request->contact_person,
            'invoice_no' => $newInvoiceNo,
            'invoice_date' => $request->invoice_date,
            'order_date' => $request->order_date,
            'order_no' => $orderNo,
            'order_no_text' => $request->order_no_text,
            'terms_condition' => $request->terms_condition,
            'sub_total' => $totalSubtotal,
            'cgst' => $totalCgst,
            'sgst' => $totalSgst,
            'igst' => $totalIgst,
            'gst' => $totalCgst + $totalSgst + $totalIgst,
            'total' => $grandTotal,
        ]);

        // Store products
        if ($request->has('products')) {
            foreach ($request->products as $product) {
                if (isset($product['product_id'])) {
                    $productModel = Product::findOrFail($product['product_id']);
                    
                    // Calculate individual GST values for this product
                    $quantity = (int)$product['quantity'];
                    $unitPrice = (float)str_replace(',', '', $product['unit_price']);
                    $subtotal = $quantity * $unitPrice;
                    
                    // Get GST percentage from product
                    $gstPercentage = $productModel->gst_percentage;
                    $isIgst = $productModel->is_igst;
                    
                    $cgstAmount = 0;
                    $sgstAmount = 0;
                    $igstAmount = 0;
                    
                    if ($isIgst) {
                        $igstAmount = ($subtotal * $gstPercentage) / 100;
                    } else {
                        $cgstAmount = ($subtotal * $gstPercentage) / 200; // Half of GST
                        $sgstAmount = ($subtotal * $gstPercentage) / 200; // Half of GST
                    }
                    
                    $totalGst = $cgstAmount + $sgstAmount + $igstAmount;
                    
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product['product_id'],
                        'service_id' => null,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'unit_type' => $productModel->unit_type,
                        'cgst' => $cgstAmount,
                        'sgst' => $sgstAmount,
                        'igst' => $igstAmount,
                        'gst' => $totalGst,
                        'total' => $subtotal + $totalGst,
                        'type' => 'product',
                    ]);

                    // Update the sold column in the stock table
                    $stock = Stock::where('product_id', $product['product_id'])->first();
                    if ($stock) {
                        $stock->increment('sold', $product['quantity']);
                    }
                }
            }
        }

        // Store services
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['service_id'])) {
                    $gstTotal = (float)str_replace(',', '', $service['gst_total'] ?? 0);
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => null,
                        'service_id' => $service['service_id'],
                        'quantity' => (int)$service['quantity'],
                        'unit_price' => (float)str_replace(',', '', $service['unit_price']),
                        'unit_type' => '-',
                        'cgst' => $gstTotal / 2,
                        'sgst' => $gstTotal / 2,
                        'igst' => 0,
                        'gst' => $gstTotal,
                        'total' => (float)str_replace(',', '', $service['total']),
                        'type' => 'service',
                    ]);
                }
            }
        }

        // Store payment record
        DB::table('payments')->insert([
            'invoice_id' => $invoice->id,
            'total_amount' => (float)str_replace(',', '', $request->grand_total ?? 0),
            'pending_amount' => (float)str_replace(',', '', $request->grand_total ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified invoice.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'items.product', 'items.service'])->findOrFail($id);
        return view('ims.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();
        $services = Service::all();
        return view('ims.invoices.edit', compact('invoice', 'customers', 'products', 'services'));
    }

    /**
     * Update the specified invoice in the database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer' => 'required|exists:customers,id',
            'contact_person' => 'required|exists:contact_persons,id',
            'invoice_date' => 'required|date',
            'order_date' => 'required|date',
            'order_no' => 'required|string',
            'grand_sub_total' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'product_total_cgst' => 'nullable|numeric|min:0',
            'product_total_sgst' => 'nullable|numeric|min:0',
            'product_total_igst' => 'nullable|numeric|min:0',
            'service_total_cgst' => 'nullable|numeric|min:0',
            'service_total_sgst' => 'nullable|numeric|min:0',
            'service_total_igst' => 'nullable|numeric|min:0',
            'products' => 'nullable|array',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.quantity' => 'required_with:products.*.product_id|integer|min:1',
            'products.*.unit_price' => 'required_with:products.*.product_id|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'nullable|exists:services,id',
            'services.*.quantity' => 'required_with:services.*.service_id|integer|min:1',
            'services.*.unit_price' => 'required_with:services.*.service_id|numeric|min:0',
        ]);

        $invoice = Invoice::findOrFail($id);

        // Revert the sold quantities for the old items
        foreach ($invoice->items as $item) {
            if ($item->product_id) { // Only revert stock for products, not services
                $stock = Stock::where('product_id', $item->product_id)->first();
                if ($stock) {
                    $stock->decrement('sold', $item->quantity);
                }
            }
        }

        // Check stock availability for new products
        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $product) {
                if (isset($product['product_id']) && !empty($product['product_id'])) {
                    $stock = Stock::where('product_id', $product['product_id'])->first();

                    if (!$stock || ($stock->quantity - $stock->sold) < $product['quantity']) {
                        $productName = Product::find($product['product_id'])->name ?? 'Unknown Product';
                        return redirect()->back()->withErrors([
                            'products' => "Product '{$productName}' is out of stock."
                        ])->withInput();
                    }
                }
            }
        }

        // Calculate totals from items instead of using frontend values
        $subTotal = 0;
        $totalCgst = 0;
        $totalSgst = 0;
        $totalIgst = 0;
        
        // Calculate product totals
        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $product) {
                if (isset($product['product_id']) && !empty($product['product_id'])) {
                    $productModel = Product::findOrFail($product['product_id']);
                    
                    $quantity = (int)$product['quantity'];
                    $unitPrice = (float)$product['unit_price'];
                    $itemSubtotal = $quantity * $unitPrice;
                    $subTotal += $itemSubtotal;
                    
                    // Calculate GST
                    $gstPercentage = $productModel->gst_percentage;
                    $isIgst = $productModel->is_igst;
                    
                    if ($isIgst) {
                        $totalIgst += ($itemSubtotal * $gstPercentage) / 100;
                    } else {
                        $totalCgst += ($itemSubtotal * $gstPercentage) / 200;
                        $totalSgst += ($itemSubtotal * $gstPercentage) / 200;
                    }
                }
            }
        }
        
        // Calculate service totals
        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $service) {
                if (isset($service['service_id']) && !empty($service['service_id'])) {
                    $quantity = (int)$service['quantity'];
                    $unitPrice = (float)$service['unit_price'];
                    $itemSubtotal = $quantity * $unitPrice;
                    $subTotal += $itemSubtotal;
                    
                    // For services, use the provided GST values (they are calculated correctly)
                    $totalCgst += (float)($service['cgst'] ?? 0);
                    $totalSgst += (float)($service['sgst'] ?? 0);
                    $totalIgst += (float)($service['igst'] ?? 0);
                }
            }
        }
        
        $totalGst = $totalCgst + $totalSgst + $totalIgst;
        $grandTotal = $subTotal + $totalGst;

        $invoice->update([
            'customer_id' => $request->customer,
            'contactperson_id' => $request->contact_person,
            'invoice_no' => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'order_date' => $request->order_date,
            'order_no' => $request->order_no,
            'sub_total' => $subTotal,
            'cgst' => $totalCgst,
            'sgst' => $totalSgst,
            'igst' => $totalIgst,
            'gst' => $totalGst,
            'total' => $grandTotal,
        ]);

        // Delete old items and add new ones
        InvoiceItem::where('invoice_id', $invoice->id)->delete();

        // Store products
        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $product) {
                if (isset($product['product_id']) && !empty($product['product_id'])) {
                    $productModel = Product::findOrFail($product['product_id']);
                    
                    // Calculate individual GST values for this product
                    $quantity = (int)$product['quantity'];
                    $unitPrice = (float)$product['unit_price'];
                    $subtotal = $quantity * $unitPrice;
                    
                    // Get GST percentage from product
                    $gstPercentage = $productModel->gst_percentage;
                    $isIgst = $productModel->is_igst;
                    
                    $cgstAmount = 0;
                    $sgstAmount = 0;
                    $igstAmount = 0;
                    
                    if ($isIgst) {
                        $igstAmount = ($subtotal * $gstPercentage) / 100;
                    } else {
                        $cgstAmount = ($subtotal * $gstPercentage) / 200; // Half of GST
                        $sgstAmount = ($subtotal * $gstPercentage) / 200; // Half of GST
                    }
                    
                    $totalGst = $cgstAmount + $sgstAmount + $igstAmount;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product['product_id'],
                        'service_id' => null,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'unit_type' => $productModel->unit_type,
                        'cgst' => $cgstAmount,
                        'sgst' => $sgstAmount,
                        'igst' => $igstAmount,
                        'gst' => $totalGst,
                        'total' => $subtotal + $totalGst,
                        'type' => 'product',
                    ]);

                    // Update the sold column in the stock table
                    $stock = Stock::where('product_id', $product['product_id'])->first();
                    if ($stock) {
                        $stock->increment('sold', $product['quantity']);
                    }
                }
            }
        }

        // Store services
        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $service) {
                if (isset($service['service_id']) && !empty($service['service_id'])) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => null,
                        'service_id' => $service['service_id'],
                        'quantity' => $service['quantity'],
                        'unit_price' => $service['unit_price'],
                        'unit_type' => '-',
                        'cgst' => $service['cgst'] ?? 0,
                        'sgst' => $service['sgst'] ?? 0,
                        'igst' => $service['igst'] ?? 0,
                        'gst' => ($service['cgst'] ?? 0) + ($service['sgst'] ?? 0) + ($service['igst'] ?? 0),
                        'total' => $service['total'],
                        'type' => 'service',
                    ]);
                }
            }
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified invoice from the database.
     */
    public function destroy($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        // Revert the sold quantities for the items in the invoice
        foreach ($invoice->items as $item) {
            $stock = Stock::where('product_id', $item->product_id)->first();
            if ($stock) {
                $stock->decrement('sold', $item->quantity);
            }
        }

        // Delete the invoice and its items
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function generatePDF($id)
    {
        try {
            $invoice = Invoice::with(['items.product', 'items.service', 'customer'])->findOrFail($id);

            // Generate QR code for invoice verification - use absolute URL
            $qrUrl = url('/ims/invoices/' . $id . '/qr-view');
            
            // Generate QR code with SVG format for better PDF compatibility
            $qrCodeSvg = QrCode::format('svg')
                ->size(80)
                ->errorCorrection('M')
                ->generate($qrUrl);
            
            // Convert SVG to data URL for PDF embedding
            $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

            $pdf = Pdf::loadView('ims.invoices.pdf', compact('invoice', 'qrCode'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

            // Sanitize filename by replacing invalid characters
            $sanitizedInvoiceNo = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $invoice->invoice_no);
            
            return $pdf->stream('Invoice_' . $sanitizedInvoiceNo . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    public function qrView($id)
    {
        try {
            $invoice = Invoice::with(['items.product', 'items.service', 'customer'])->findOrFail($id);
            
            // Generate QR code URL for the invoice - use absolute URL
            $qrUrl = url('/ims/invoices/' . $id . '/qr-view');
            
            // Generate QR code with proper format for web display
            $qrCode = QrCode::format('svg')
                ->size(200)
                ->errorCorrection('M')
                ->generate($qrUrl);
            
            return view('ims.invoices.qr-view', compact('invoice', 'qrCode'));
        } catch (\Exception $e) {
            Log::error('QR View Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading invoice QR view: ' . $e->getMessage());
        }
    }
}
