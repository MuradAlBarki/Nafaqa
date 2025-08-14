<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\DivorceCase;
use App\Models\Payment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    /**
     * List payments for a given divorce case.
     */
    public function index(DivorceCase $divorceCase)
    {
        $payments = $divorceCase->payments()->paginate(15);

        return view('payments.index', compact('divorceCase', 'payments'));
    }

       public function create(DivorceCase $divorceCase)
    {
        $this->authorize('create', [Payment::class, $divorceCase]);

        return view('payments.create', compact('divorceCase'));
    }

    /**
     * Store a new payment for the divorce case.
     */
    public function store(Request $request, DivorceCase $divorceCase)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_for_date' => 'required|date',
        ]);

        $divorceCase->payments()->create($validated);

        return redirect()
            ->route('obligations.show', [$divorceCase, $divorceCase->obligation])
            ->with('success', __('Payment added successfully.'));
    }

      public function edit(DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('update', $payment);

        return view('payments.edit', compact('divorceCase', 'payment'));
    }

    /**
     * Update an existing payment.
     */
    public function update(Request $request, DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('update', $payment);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_for_date' => 'required|date',
        ]);

        $payment->update($validated);

        return redirect()
            ->route('payments.index', $divorceCase)
            ->with('success', __('Payment updated successfully.'));
    }

    /**
     * Mark a payment as paid with proof document.
     */
    public function pay(Request $request, DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('pay', $payment);

        if ($payment->status !== PaymentStatusEnum::Entry) {
            return back()->withErrors(__('This payment is already processed.'));
        }

        $validated = $request->validate([
            'proof_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['proof_document_url'] = $request->file('proof_document')
            ->storeAs(
                'proof_documents',
                uniqid().'_'.$request->file('proof_document')->getClientOriginalName(),
                'public'
            );

        $validated['status'] = PaymentStatusEnum::PaidNotVerified->value;
        $validated['payment_date'] = today();

        $payment->update($validated);

        return redirect()
            ->route('payments.index', $divorceCase)
            ->with('success', __('Payment marked as paid.'));
    }

    /**
     * Toggle payment status manually (admin or system use).
     */
    public function toggleStatus(Request $request, Payment $payment)
    {

        $request->validate([
            'status' => ['required', new Enum(PaymentStatusEnum::class)],
        ]);

        $payment->status = $request->status;
        $payment->save();

        return redirect()
            ->route('payments.index', $payment->divorceCase)
            ->with('success', __('Payment status updated successfully.'));
    }
}