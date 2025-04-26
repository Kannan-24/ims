<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;

use App\Models\ims\Payment;
use App\Models\ims\PaymentItem;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Show all payments
    public function index()
    {
        $query = Payment::with('invoice');

        if ($search = request('search')) {
            $query->whereHas('invoice', function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%");
            });
        }

        if ($from = request('from')) {
            $query->whereHas('invoice', function ($q) use ($from) {
                $q->whereDate('invoice_date', '>=', $from);
            });
        }

        if ($to = request('to')) {
            $query->whereHas('invoice', function ($q) use ($to) {
                $q->whereDate('invoice_date', '<=', $to);
            });
        }

        $payments = $query->latest()->get();

        if (request()->ajax()) {
            return response()->json([
                'html' => view('payments.partials.list', compact('payments'))->render(),
            ]);
        }

        return view('ims.payments.index', compact('payments'));
    }

    // Show payment details
    public function show($id)
    {
        $payment = Payment::with('items', 'invoice')->findOrFail($id);
        return view('ims.payments.show', compact('payment'));
    }

    // Show form to add a new payment item (partial or full)
    public function create($paymentId = null)
    {
        $payment = $paymentId ? Payment::findOrFail($paymentId) : null;
        return view('ims.payments.create', compact('payment'));
    }

    // Store a new payment item (partial or full)
    public function store(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', function ($attribute, $value, $fail) use ($payment) {
                if ($value > $payment->pending_amount) {
                    $fail("The entered amount exceeds the pending amount of ₹{$payment->pending_amount}.");
                }
            }],
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string',
            'payment_method' => 'required|in:cash,cheque,upi,bank_transfer',
        ]);

        PaymentItem::create([
            'payment_id' => $paymentId,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'payment_method' => $request->payment_method,
        ]);

        // Recalculate payment status
        $paidAmount = $payment->items()->sum('amount');
        $payment->paid_amount = $paidAmount;
        $payment->pending_amount = $payment->total_amount - $paidAmount;

        if ($payment->pending_amount <= 0) {
            $payment->status = 'paid';
        } elseif ($paidAmount > 0) {
            $payment->status = 'partial';
        } else {
            $payment->status = 'unpaid';
        }

        $payment->save();

        return redirect()->route('payments.show', $paymentId)->with('success', 'Payment item added successfully.');
    }

    // Show form to edit payment item
    public function edit($id)
    {
        $item = PaymentItem::findOrFail($id);
        $payment = $item->payment; // Retrieve the associated payment
        return view('ims.payments.edit', compact('item', 'payment'));
    }

    // Update payment item in the database
    public function update(Request $request, $id)
    {
        $item = PaymentItem::findOrFail($id);
        $payment = $item->payment;

        // Calculate sum of all other items excluding the current item
        $otherPaid = $payment->items()->where('id', '!=', $item->id)->sum('amount');
        $maxAmount = $payment->total_amount - $otherPaid;

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', function ($attribute, $value, $fail) use ($maxAmount) {
                if ($value > $maxAmount) {
                    $fail("The entered amount exceeds the available limit of ₹{$maxAmount}.");
                }
            }],
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string',
            'payment_method' => 'required|in:cash,cheque,upi,bank_transfer',
        ]);

        $item->update([
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'payment_method' => $request->payment_method,
        ]);

        // Recalculate totals
        $paidAmount = $payment->items()->sum('amount');
        $payment->paid_amount = $paidAmount;
        $payment->pending_amount = $payment->total_amount - $paidAmount;

        // Update status
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

        $item->delete();

        $paidAmount = $payment->items()->sum('amount');
        $payment->paid_amount = $paidAmount;
        $payment->pending_amount = $payment->total_amount - $paidAmount;

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
