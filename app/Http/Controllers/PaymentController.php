<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PaymentItem;
// Removed unused Invoice import

class PaymentController extends Controller
{
    // Show all payments
    public function index()
    {
        $payments = Payment::with('invoice')->latest()->get();
        return view('payments.index', compact('payments'));
    }

    // Show payment details
    public function show($id)
    {
        $payment = Payment::with('items', 'invoice')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    // Show form to add a new payment item (partial or full)
    public function create($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);  // Fetch the payment using the ID
        return view('payments.create', compact('payment'));  // Pass payment to the view
    }

    // Store a new payment item (partial or full)
    public function store(Request $request, $paymentId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string',
            'payment_method' => 'required|in:cash,cheque,upi,bank_transfer',
        ]);

        // Find the payment
        $payment = Payment::findOrFail($paymentId);

        // Store the payment item
        PaymentItem::create([
            'payment_id' => $paymentId,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'payment_method' => $request->payment_method,
        ]);

        // Recalculate payment status
        $paidAmount = $payment->items()->sum('amount');
        if ($paidAmount >= $payment->total_amount) {
            $payment->status = 'paid';
        } elseif ($paidAmount > 0) {
            $payment->status = 'partial';
        } else {
            $payment->status = 'unpaid';
        }

        // Update the paid and pending amounts
        $payment->paid_amount = $paidAmount;
        $payment->pending_amount = $payment->total_amount - $paidAmount;
        $payment->save();

        return redirect()->route('payments.show', $paymentId)->with('success', 'Payment item added successfully.');
    }

    // Show form to edit payment item
    public function edit($id)
    {
        $item = PaymentItem::findOrFail($id);
        return view('payments.edit', compact('item'));
    }

    // Update payment item in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string',
            'payment_method' => 'required|in:cash,cheque,upi,bank_transfer',
        ]);

        $item = PaymentItem::findOrFail($id);
        $item->update([
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'payment_method' => $request->payment_method,
        ]);

        // Recalculate the total paid amount for the payment
        $payment = $item->payment;
        $paidAmount = $payment->items()->sum('amount');
        $payment->paid_amount = $paidAmount;
        $payment->pending_amount = $payment->total_amount - $paidAmount;

        // Recalculate payment status
        if ($payment->pending_amount <= 0) {
            $payment->status = 'paid';
        } elseif ($payment->paid_amount > 0) {
            $payment->status = 'partial';
        } else {
            $payment->status = 'unpaid';
        }
        $payment->save();

        return redirect()->route('payments.show', $payment->id)->with('success', 'Payment item updated successfully.');
    }

    // Delete payment item
    public function destroy($id)
    {
        $item = PaymentItem::findOrFail($id);
        $payment = $item->payment;

        // Delete the payment item
        $item->delete();

        // Recalculate the total paid amount for the payment
        $paidAmount = $payment->items()->sum('amount');
        $payment->paid_amount = $paidAmount;
        $payment->pending_amount = $payment->total_amount - $paidAmount;

        // Recalculate payment status
        if ($payment->pending_amount <= 0) {
            $payment->status = 'paid';
        } elseif ($payment->paid_amount > 0) {
            $payment->status = 'partial';
        } else {
            $payment->status = 'unpaid';
        }
        $payment->save();

        return redirect()->route('payments.show', $payment->id)->with('success', 'Payment item deleted successfully.');
    }
}
