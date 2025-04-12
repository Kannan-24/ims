<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Customer;
use App\Models\ContactPerson;
use App\Models\Product;
use App\Models\Service;
use PDF; // Import PDF class from the package


class QuotationController extends Controller
{
    /**
     * Display a listing of the quotations.
     */
    public function index()
    {
        $quotations = Quotation::with('customer')->latest()->get();
        return view('quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new quotation.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('stock')->get();
        $services = Service::all();
        return view('quotations.create', compact('customers', 'products', 'services'));
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
        $lastQuotation = Quotation::latest('id')->first();
        $quotationCode = 'QUO-' . str_pad(($lastQuotation ? $lastQuotation->id + 1 : 1), 3, '0', STR_PAD_LEFT);

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
            'sub_total' => $request->product_subtotal + $request->service_subtotal,
            'cgst' => $request->product_total_cgst + ($totalServiceGst / 2),
            'sgst' => $request->product_total_sgst + ($totalServiceGst / 2),
            'igst' => $request->product_total_igst,
            'gst' => $request->product_total_cgst + $request->product_total_sgst + $request->product_total_igst + $totalServiceGst,
            'total' => $request->grand_total,
        ]);

        // Store products
        if ($request->has('products')) {
            foreach ($request->products as $product) {
                if (isset($product['product_id'])) {
                    $productModel = Product::findOrFail($product['product_id']);
                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => $product['product_id'],
                        'service_id' => null,
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'unit_type' => $productModel->unit_type,
                        'cgst' => $request->product_total_cgst,
                        'sgst' => $request->product_total_sgst,
                        'igst' => $request->product_total_igst,
                        'gst' => $request->product_total_cgst + $request->product_total_sgst + $request->product_total_igst,
                        'total' => $product['total'],
                        'type' => 'product',
                    ]);
                }
            }
        }

        // Store services
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['service_id'])) {
                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => null,
                        'service_id' => $service['service_id'],
                        'quantity' => $service['quantity'],
                        'unit_price' => $service['unit_price'],
                        'unit_type' => '-',
                        'cgst' => $service['gst_total'] / 2,
                        'sgst' => $service['gst_total'] / 2,
                        'igst' => 0,
                        'gst' => $service['gst_total'],
                        'total' => $service['total'],
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
        return view('quotations.show', compact('quotation'));
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
        return view('quotations.edit', compact('quotation', 'customers', 'products', 'services'));
    }

    /**
     * Update the specified quotation in the database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quotation_date' => 'required|date',
            'terms_condition' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.quantity' => 'required_with:products.*.product_id|integer|min:1',
            'products.*.unit_price' => 'required_with:products.*.product_id|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'nullable|exists:services,id',
            'services.*.quantity' => 'required_with:services.*.service_id|integer|min:1',
            'services.*.unit_price' => 'required_with:services.*.service_id|numeric|min:0',
            'services.*.gst_total' => 'nullable|numeric|min:0',
        ]);

        $totalServiceGst = 0;

        // Calculate total GST from services
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['gst_total'])) {
                    $totalServiceGst += $service['gst_total'];
                }
            }
        }

        $quotation = Quotation::findOrFail($id);
        $quotation->update([
            'customer_id' => $request->customer_id,
            'quotation_code' => $request->quotation_code,
            'quotation_date' => $request->quotation_date,
            'terms_condition' => $request->terms_condition,
            'sub_total' => $request->sub_total,
            'cgst' => $request->total_cgst,
            'sgst' => $request->total_sgst,
            'igst' => $request->total_igst,
            'gst' => $request->total_cgst + $request->total_sgst + $request->total_igst + $totalServiceGst,
            'total' => $request->total,
        ]);

        // Delete old items and add new ones
        QuotationItem::where('quotation_id', $quotation->id)->delete();

        // Update products
        if ($request->has('products')) {
            foreach ($request->products as $product) {
                if (isset($product['product_id'])) {
                    $productModel = Product::findOrFail($product['product_id']);
                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => $product['product_id'],
                        'service_id' => null,
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'unit_type' => $productModel->unit_type,
                        'cgst' => ($product['unit_price'] * $product['cgst']) / 100,
                        'sgst' => ($product['unit_price'] * $product['sgst']) / 100,
                        'igst' => ($product['unit_price'] * $product['igst']) / 100,
                        'gst' => 0,
                        'total' => $product['total'],
                        'type' => 'product',
                    ]);
                }
            }
        }

        // Update services
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['service_id'])) {
                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => null,
                        'service_id' => $service['service_id'],
                        'quantity' => $service['quantity'],
                        'unit_price' => $service['unit_price'],
                        'unit_type' => '-',
                        'cgst' => 0,
                        'sgst' => 0,
                        'igst' => 0,
                        'gst' => $service['gst_total'],
                        'total' => $service['total'],
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
        $quotation = Quotation::with('customer', 'items.product')->findOrFail($id);

        $pdf = PDF::loadView('quotations.pdf', compact('quotation'));

        // Show the PDF in the browser
        return $pdf->stream('quotation_' . $quotation->quotation_code . '.pdf');
    }


}
