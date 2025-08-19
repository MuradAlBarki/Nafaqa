<?php

namespace App\Http\Controllers;

use App\EpaymentStatusEnum;
use App\Exports\LatePaymentsExport;
use App\PaymentStatusEnum;
use App\Models\DivorceCase;
use App\Models\Epayment;
use App\Models\Payment;
use App\Notifications\UserAlertNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    /**
     * List payments for a given divorce case.
     */
    public function index(DivorceCase $divorceCase)
    {
        $this->authorize('viewAny', [Payment::class, $divorceCase]);

        $payments = $divorceCase->payments()->paginate(5);

        return view('payments.index', compact('divorceCase', 'payments'));
    }

       public function create(DivorceCase $divorceCase)
    {
        $this->authorize('create', [Payment::class, $divorceCase]);

        return view('payments.create', compact('divorceCase'));
    }

       public function show(DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('show', [$payment, $divorceCase]);

        return view('payments.show', compact('divorceCase', 'payment'));
    }

    /**
     * Store a new payment for the divorce case.
     */
    public function store(Request $request, DivorceCase $divorceCase)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $exists = $divorceCase->payments()
        ->whereDate('due_date', $validated['due_date'])
        ->where('status', PaymentStatusEnum::Entry->value)
        ->exists();

        if ($exists) {
            return redirect()
                ->route('divorce-cases.obligations.show', [$divorceCase, $divorceCase->obligation])
                ->withErrors([ __('A pending payment already exists for this date.')]);
        }

        $divorceCase->payments()->create($validated);

        return redirect()
            ->route('divorce-cases.obligations.show', [$divorceCase, $divorceCase->obligation])
            ->with('success', __('Payment added successfully.'));
    }

      public function edit(DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('update', $payment);

        $status = PaymentStatusEnum::cases();
        return view('payments.edit', compact('divorceCase', 'payment', 'status'));
    }

      public function listEpay(DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('pay', $payment);

        return view('payments.epay', compact('payment'));
    }

    /**
     * Update an existing payment.
     */
    public function update(Request $request, DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('update', $payment);

        $validated = $request->validate([
        'amount' => 'required|numeric|min:0',
        'due_date' => 'required|date',
        'status' => ['required', new Enum(PaymentStatusEnum::class)],
    ]);

        $payment->update($validated);

        return redirect()
            ->route('divorce-cases.obligations.show', [$divorceCase, $divorceCase->obligation])
            ->with('success', __('Payment updated successfully.'));
    }

    


    public function review(Request $request, Payment $payment)
    {
    $this->authorize('changeStatus', $payment);

    $request->validate([
        'status' => ['required', new Enum(PaymentStatusEnum::class)],
    ]);

    $payment->status = $request->status;
    $payment->save();

    $divorceCase = $payment->divorceCase;

    if ($divorceCase->isMother(auth()->user())) {
        return redirect()
            ->route('divorce-cases.payments.index', $divorceCase)
            ->with('success', __('Payment updated successfully.'));
    }

    return redirect()
        ->route('divorce-cases.obligations.show', [$divorceCase, $divorceCase->obligation])
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
               ->route('divorce-cases.obligations.show', [$divorceCase, $divorceCase->obligation])
            ->with('success', __('Payment marked as paid.'));
    }

    public function success(Request $request, Payment $payment)
    {
        Epayment::create([
            'payment_id' => $payment->id,
            'status' => EpaymentStatusEnum::Success->value,
            'gateway' => $request->input('gateway'),
            'response_json' => $request->input('response')
        ]);

        $payment->status = PaymentStatusEnum::PaidNotVerified;
        $payment->save();

        auth()->user()->notify(new UserAlertNotification(
        'Payment Successful',
        "Your payment #{$payment->id} was successful.",
        'payment',
        route('payments.show', $payment)
));


    }

    public function fail(Request $request, Payment $payment)
    {
        Epayment::create([
            'payment_id' => $payment->id,
            'status' => EpaymentStatusEnum::Failed->value,
            'gateway' => $request->input('gateway'),
            'response_json' => $request->input('response')
        ]);
    }


    public function exportLatePayments()
    {
    $this->authorize('exportLatePayments', Payment::class);

    return Excel::download(new LatePaymentsExport, 'late_payments.xlsx');
    }
}