<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;
use App\Models\ims\DeliveryChallan;
use App\Models\ims\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DeliveryChallanController extends Controller
{
    public function index()
    {
        $deliveryChallans = DeliveryChallan::with('invoice.customer')->latest()->get();
        return view('ims.delivery-challans.index', compact('deliveryChallans'));
    }

    public function show($id)
    {
        $deliveryChallan = DeliveryChallan::with([
            'invoice.customer', 
            'invoice.items' => function($query) {
                $query->where('type', 'product')
                      ->whereNotNull('product_id')
                      ->with('product');
            }
        ])->findOrFail($id);
        return view('ims.delivery-challans.show', compact('deliveryChallan'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id'
        ]);

        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($request->invoice_id);
        
        // Check if invoice has any products (physical goods) for delivery
        $hasProducts = $invoice->items()->where('type', 'product')->whereNotNull('product_id')->exists();
        
        if (!$hasProducts) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot generate delivery challan - this invoice contains only services, no physical products to deliver.'
            ], 422);
        }

        // Check if delivery challan already exists for this invoice
        $existingChallan = DeliveryChallan::where('invoice_id', $invoice->id)->first();

        if ($existingChallan) {
            // Update existing delivery challan
            $existingChallan->update([
                'delivery_date' => now()->toDateString(),
                'generated_at' => now()
            ]);

            $deliveryChallan = $existingChallan;
        } else {
            // Create new delivery challan
            $deliveryChallan = DeliveryChallan::create([
                'dc_no' => DeliveryChallan::generateDcNumber(),
                'invoice_id' => $invoice->id,
                'delivery_date' => now()->toDateString(),
                'generated_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Delivery challan generated successfully',
            'delivery_challan' => $deliveryChallan,
            'pdf_url' => route('delivery-challans.pdf', $deliveryChallan->id)
        ]);
    }

    public function pdf($id)
    {
        $deliveryChallan = DeliveryChallan::with([
            'invoice.customer', 
            'invoice.items' => function($query) {
                $query->where('type', 'product')
                      ->whereNotNull('product_id')
                      ->with('product');
            }
        ])->findOrFail($id);
        $invoice = $deliveryChallan->invoice;

        // Generate QR code for the related invoice
        $qrUrl = url('/ims/invoices/' . $invoice->id . '/qr-view');
        $qrCodeSvg = QrCode::format('svg')
            ->size(60)
            ->errorCorrection('M')
            ->generate($qrUrl);
        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        // Sanitize filename for PDF
        $filename = 'DC-' . str_replace(['/', '\\'], '-', $deliveryChallan->dc_no) . '.pdf';

        $pdf = Pdf::loadView('ims.delivery-challans.pdf', compact('deliveryChallan', 'invoice', 'qrCode'));

        return $pdf->stream($filename);
    }

    public function download($id)
    {
        $deliveryChallan = DeliveryChallan::with([
            'invoice.customer', 
            'invoice.items' => function($query) {
                $query->where('type', 'product')
                      ->whereNotNull('product_id')
                      ->with('product');
            }
        ])->findOrFail($id);
        $invoice = $deliveryChallan->invoice;

        // Generate QR code for the related invoice
        $qrUrl = url('/ims/invoices/' . $invoice->id . '/qr-view');
        $qrCodeSvg = QrCode::format('svg')
            ->size(60)
            ->errorCorrection('M')
            ->generate($qrUrl);
        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        // Sanitize filename for PDF
        $filename = 'DC-' . str_replace(['/', '\\'], '-', $deliveryChallan->dc_no) . '.pdf';

        $pdf = Pdf::loadView('ims.delivery-challans.pdf', compact('deliveryChallan', 'invoice', 'qrCode'));

        return $pdf->download($filename);
    }

    // Status functionality removed as requested

    public function destroy($id)
    {
        try {
            $deliveryChallan = DeliveryChallan::findOrFail($id);
            $deliveryChallan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Delivery challan deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete delivery challan: ' . $e->getMessage()
            ], 500);
        }
    }
}
