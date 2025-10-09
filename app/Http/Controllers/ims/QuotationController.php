<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ims\Quotation;
use App\Models\ims\QuotationItem;
use App\Models\ims\Customer;
use App\Models\ims\ContactPerson;
use App\Models\ims\Product;
use App\Models\ims\Service;
use App\Models\ims\Invoice;
use App\Models\ims\InvoiceItem;
use App\Models\ims\Stock;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;


class QuotationController extends Controller
{
    /**
     * Display a listing of the quotations.
     */
    public function index()
    {
        $query = Quotation::with(['customer', 'customer.contactPersons']);

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('quotation_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($from = request('from')) {
            $query->whereDate('quotation_date', '>=', $from);
        }

        if ($to = request('to')) {
            $query->whereDate('quotation_date', '<=', $to);
        }

        $quotations = $query->latest()->paginate(15);

        return view('ims.quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new quotation.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('stock')->get();
        $services = Service::all();
        return view('ims.quotations.create', compact('customers', 'products', 'services'));
    }

    /**
     * Store a newly created quotation in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required|exists:customers,id',
            'contact_person' => 'required|exists:contact_persons,id',
            'quotation_date' => 'required|date',
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

        // Generate the quotation code automatically
        $currentYear = date('Y');
        $nextYear = date('y', strtotime('+1 year'));
        $previousYear = date('y', strtotime('-1 year'));
        $financialYear = (date('m') >= 4) ? $currentYear . '-' . $nextYear : $previousYear . '-' . date('y');

        $lastQuotation = Quotation::where('quotation_code', 'like', 'QUO/' . $financialYear . '/%')->latest('id')->first();
        $lastQuotationNumber = $lastQuotation ? (int)explode('/', $lastQuotation->quotation_code)[2] : 0;
        $quotationCode = 'QUO/' . $financialYear . '/' . ($lastQuotationNumber + 1);

        $totalServiceGst = 0;

        // Calculate total GST from services
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['gst_total'])) {
                    $totalServiceGst += $service['gst_total'];
                }
            }
        }

        $quotation = Quotation::create([
            'customer_id' => $request->customer,
            'contactperson_id' => $request->contact_person,
            'quotation_code' => $quotationCode,
            'quotation_date' => $request->quotation_date,
            'terms_condition' => $request->terms_condition,
            'sub_total' => (float)str_replace(',', '', $request->product_subtotal ?? 0) + (float)str_replace(',', '', $request->service_subtotal ?? 0),
            'cgst' => (float)str_replace(',', '', $request->product_total_cgst ?? 0) + ($totalServiceGst / 2),
            'sgst' => (float)str_replace(',', '', $request->product_total_sgst ?? 0) + ($totalServiceGst / 2),
            'igst' => (float)str_replace(',', '', $request->product_total_igst ?? 0),
            'gst' => (float)str_replace(',', '', $request->product_total_cgst ?? 0) + (float)str_replace(',', '', $request->product_total_sgst ?? 0) + (float)str_replace(',', '', $request->product_total_igst ?? 0) + $totalServiceGst,
            'total' => (float)str_replace(',', '', $request->grand_total ?? 0),
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

                    // Use direct discount amount
                    $discountAmount = (float)str_replace(',', '', $product['discount_amount'] ?? 0);
                    // Ensure discount doesn't exceed subtotal
                    $discountAmount = min($discountAmount, $subtotal);
                    $taxableAmount = $subtotal - $discountAmount;

                    // Get GST percentage from product
                    $gstPercentage = $productModel->gst_percentage;
                    $isIgst = $productModel->is_igst;

                    $cgstAmount = 0;
                    $sgstAmount = 0;
                    $igstAmount = 0;

                    if ($isIgst) {
                        $igstAmount = ($taxableAmount * $gstPercentage) / 100;
                    } else {
                        $cgstAmount = ($taxableAmount * $gstPercentage) / 200; // Half of GST
                        $sgstAmount = ($taxableAmount * $gstPercentage) / 200; // Half of GST
                    }

                    $totalGst = $cgstAmount + $sgstAmount + $igstAmount;

                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => $product['product_id'],
                        'service_id' => null,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount_percentage' => ($discountAmount > 0 && $subtotal > 0) ? round(($discountAmount / $subtotal) * 100, 2) : 0,
                        'discount_amount' => $discountAmount,
                        'taxable_amount' => $taxableAmount,
                        'unit_type' => $productModel->unit_type,
                        'cgst' => $cgstAmount,
                        'sgst' => $sgstAmount,
                        'igst' => $igstAmount,
                        'gst' => $totalGst,
                        'total' => $taxableAmount + $totalGst,
                        'type' => 'product',
                    ]);
                }
            }
        }

        // Store services
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['service_id'])) {
                    $quantity = (int)$service['quantity'];
                    $unitPrice = (float)str_replace(',', '', $service['unit_price']);
                    $subtotal = $quantity * $unitPrice;

                    // Use direct discount amount
                    $discountAmount = (float)str_replace(',', '', $service['discount_amount'] ?? 0);
                    // Ensure discount doesn't exceed subtotal
                    $discountAmount = min($discountAmount, $subtotal);
                    $taxableAmount = $subtotal - $discountAmount;

                    $gstTotal = (float)str_replace(',', '', $service['gst_total'] ?? 0);

                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => null,
                        'service_id' => $service['service_id'],
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount_percentage' => ($discountAmount > 0 && $subtotal > 0) ? round(($discountAmount / $subtotal) * 100, 2) : 0,
                        'discount_amount' => $discountAmount,
                        'taxable_amount' => $taxableAmount,
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

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
    }

    /**
     * Display the specified quotation.
     */
    public function show($id)
    {
        $quotation = Quotation::with(['customer', 'items.product', 'items.service'])->findOrFail($id);
        return view('ims.quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified quotation.
     */
    public function edit($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();
        $services = Service::all();
        return view('ims.quotations.edit', compact('quotation', 'customers', 'products', 'services'));
    }

    /**
     * Update the specified quotation in the database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer' => 'required|exists:customers,id',
            'contact_person' => 'required|exists:contact_persons,id',
            'quotation_date' => 'required|date',
            'terms_condition' => 'nullable|string',
            'grand_sub_total' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'product_total_cgst' => 'nullable|numeric|min:0',
            'product_total_sgst' => 'nullable|numeric|min:0',
            'product_total_igst' => 'nullable|numeric|min:0',
            'service_total_cgst' => 'nullable|numeric|min:0',
            'service_total_sgst' => 'nullable|numeric|min:0',
            'products' => 'nullable|array',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.quantity' => 'required_with:products.*.product_id|integer|min:1',
            'products.*.unit_price' => 'required_with:products.*.product_id|numeric|min:0',
            'products.*.discount_amount' => 'nullable|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'nullable|exists:services,id',
            'services.*.quantity' => 'required_with:services.*.service_id|integer|min:1',
            'services.*.unit_price' => 'required_with:services.*.service_id|numeric|min:0',
            'services.*.discount_amount' => 'nullable|numeric|min:0',
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->update([
            'customer_id' => $request->customer,
            'contactperson_id' => $request->contact_person,
            'quotation_date' => $request->quotation_date,
            'terms_condition' => $request->terms_condition,
            'sub_total' => $request->grand_sub_total,
            'cgst' => ($request->product_total_cgst ?? 0) + ($request->service_total_cgst ?? 0),
            'sgst' => ($request->product_total_sgst ?? 0) + ($request->service_total_sgst ?? 0),
            'igst' => ($request->product_total_igst ?? 0),
            'gst' => (($request->product_total_cgst ?? 0) + ($request->service_total_cgst ?? 0)) +
                (($request->product_total_sgst ?? 0) + ($request->service_total_sgst ?? 0)) +
                ($request->product_total_igst ?? 0),
            'total' => $request->grand_total,
        ]);

        // Delete old items and add new ones
        QuotationItem::where('quotation_id', $quotation->id)->delete();

        // Store products
        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $product) {
                if (isset($product['product_id']) && !empty($product['product_id'])) {
                    $productModel = Product::findOrFail($product['product_id']);

                    // Calculate individual GST values for this product
                    $quantity = (int)$product['quantity'];
                    $unitPrice = (float)$product['unit_price'];
                    $subtotal = $quantity * $unitPrice;

                    // Use direct discount amount
                    $discountAmount = (float)($product['discount_amount'] ?? 0);
                    // Ensure discount doesn't exceed subtotal
                    $discountAmount = min($discountAmount, $subtotal);
                    $taxableAmount = $subtotal - $discountAmount;

                    // Get GST percentage from product
                    $gstPercentage = $productModel->gst_percentage;
                    $isIgst = $productModel->is_igst;

                    $cgstAmount = 0;
                    $sgstAmount = 0;
                    $igstAmount = 0;

                    if ($isIgst) {
                        $igstAmount = ($taxableAmount * $gstPercentage) / 100;
                    } else {
                        $cgstAmount = ($taxableAmount * $gstPercentage) / 200; // Half of GST
                        $sgstAmount = ($taxableAmount * $gstPercentage) / 200; // Half of GST
                    }

                    $totalGst = $cgstAmount + $sgstAmount + $igstAmount;

                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => $product['product_id'],
                        'service_id' => null,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount_percentage' => ($discountAmount > 0 && $subtotal > 0) ? round(($discountAmount / $subtotal) * 100, 2) : 0,
                        'discount_amount' => $discountAmount,
                        'taxable_amount' => $taxableAmount,
                        'unit_type' => $productModel->unit_type,
                        'cgst' => $cgstAmount,
                        'sgst' => $sgstAmount,
                        'igst' => $igstAmount,
                        'gst' => $totalGst,
                        'total' => $taxableAmount + $totalGst,
                        'type' => 'product',
                    ]);
                }
            }
        }

        // Store services
        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $service) {
                if (isset($service['service_id']) && !empty($service['service_id'])) {
                    $quantity = (int)$service['quantity'];
                    $unitPrice = (float)$service['unit_price'];
                    $subtotal = $quantity * $unitPrice;

                    // Use direct discount amount
                    $discountAmount = (float)($service['discount_amount'] ?? 0);
                    // Ensure discount doesn't exceed subtotal
                    $discountAmount = min($discountAmount, $subtotal);
                    $taxableAmount = $subtotal - $discountAmount;

                    $gstTotal = (float)($service['gst_total'] ?? 0);

                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => null,
                        'service_id' => $service['service_id'],
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount_percentage' => ($discountAmount > 0 && $subtotal > 0) ? round(($discountAmount / $subtotal) * 100, 2) : 0,
                        'discount_amount' => $discountAmount,
                        'taxable_amount' => $taxableAmount,
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

        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully.');
    }

    /**
     * Remove the specified quotation from the database.
     */
    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }



    public function generatePDF($id)
    {
        $quotation = Quotation::with(['items', 'customer'])->findOrFail($id);

        // Generate a secure link for downloading or viewing (use public route for QR)
        $downloadUrl = route('quotation.public.download', ['id' => $quotation->id]);

        // Generate QR code with SVG format for better PDF compatibility (no ImageMagick needed)
        $qrCodeSvg = QrCode::format('svg')
            ->size(80)
            ->errorCorrection('M')
            ->generate($downloadUrl);

        // Convert SVG to data URL for PDF embedding
        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        $pdf = Pdf::loadView('ims.quotations.pdf', compact('quotation', 'qrCode'))
            ->setPaper('a4', 'portrait');

        $sanitizedQuotationNo = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $quotation->quotation_no);

        return $pdf->stream('Quotation_' . $sanitizedQuotationNo . '.pdf');
    }

    /**
     * Generate PDF for public download (no authentication required)
     */
    public function publicDownloadPDF($id)
    {
        $quotation = Quotation::with(['items', 'customer'])->findOrFail($id);

        // Generate a secure link for downloading or viewing
        $downloadUrl = route('quotation.public.download', ['id' => $quotation->id]);

        // Generate QR code with SVG format for better PDF compatibility (no ImageMagick needed)
        $qrCodeSvg = QrCode::format('svg')
            ->size(80)
            ->errorCorrection('M')
            ->generate($downloadUrl);

        // Convert SVG to data URL for PDF embedding
        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        $pdf = Pdf::loadView('ims.quotations.pdf', compact('quotation', 'qrCode'))
            ->setPaper('a4', 'portrait');

        $sanitizedQuotationNo = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $quotation->quotation_no);

        return $pdf->download('Quotation_' . $sanitizedQuotationNo . '.pdf');
    }


    /**
     * Convert quotation to invoice.
     */
    public function convertToInvoice($id)
    {
        $quotation = Quotation::with(['items', 'customer'])->findOrFail($id);

        // Generate the invoice number automatically
        $currentYear = date('Y');
        $nextYear = date('y', strtotime('+1 year'));
        $previousYear = date('y', strtotime('-1 year'));
        $financialYear = (date('m') >= 4) ? $currentYear . '-' . $nextYear : $previousYear . '-' . date('y');

        $lastInvoice = Invoice::where('invoice_no', 'like', 'INV/' . $financialYear . '/%')->latest('id')->first();
        $lastInvoiceNumber = $lastInvoice ? (int)explode('/', $lastInvoice->invoice_no)[2] : 0;
        $newInvoiceNo = 'INV/' . $financialYear . '/' . ($lastInvoiceNumber + 1);

        // Create new invoice
        $invoice = Invoice::create([
            'customer_id' => $quotation->customer_id,
            'contactperson_id' => $quotation->contactperson_id,
            'invoice_no' => $newInvoiceNo,
            'invoice_date' => now()->format('Y-m-d'),
            'order_date' => now()->format('Y-m-d'),
            'order_no' => 'Converted from Quotation ' . $quotation->quotation_code,
            'terms_condition' => $quotation->terms_condition,
            'sub_total' => $quotation->sub_total,
            'cgst' => $quotation->cgst,
            'sgst' => $quotation->sgst,
            'igst' => $quotation->igst,
            'gst' => $quotation->gst,
            'total' => $quotation->total,
            'courier_charges' => 0,
            'grand_total' => $quotation->total,
        ]);

        // Copy quotation items to invoice items
        foreach ($quotation->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item->product_id,
                'service_id' => $item->service_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount_percentage' => $item->discount_percentage ?? 0,
                'discount_amount' => $item->discount_amount ?? 0,
                'taxable_amount' => $item->taxable_amount ?? ($item->unit_price * $item->quantity),
                'unit_type' => $item->unit_type,
                'cgst' => $item->cgst,
                'sgst' => $item->sgst,
                'igst' => $item->igst,
                'gst' => $item->gst,
                'total' => $item->total,
                'type' => $item->type,
            ]);

            // Update stock for products
            if ($item->product_id) {
                $stock = Stock::where('product_id', $item->product_id)->first();
                if ($stock) {
                    $stock->increment('sold', $item->quantity);
                }
            }
        }

        // Store payment record
        DB::table('payments')->insert([
            'invoice_id' => $invoice->id,
            'total_amount' => $invoice->total,
            'pending_amount' => $invoice->total,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Quotation converted to invoice successfully! Invoice Number: ' . $newInvoiceNo);
    }
}
