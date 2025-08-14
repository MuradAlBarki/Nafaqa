<?php

namespace App\Http\Controllers;

use App\Models\DivorceCase;
use App\Models\Payment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, DivorceCase $divorceCase)
    {
        $this->authorize('create', [Payment::class, $divorceCase]);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'proof_document_url' => 'nullable|url',
            'status' => 'required|integer',
        ]);

        $payment = $divorceCase->payments()->create($validated);

        return response()->json($payment, 201);
    }

    public function show(DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('view', $payment);

        return response()->json($payment);
    }

    public function update(Request $request, DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('update', $payment);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'proof_document_url' => 'nullable|url',
            'status' => 'required|integer',
        ]);

        $payment->update($validated);

        return response()->json($payment);
    }

    public function destroy(DivorceCase $divorceCase, Payment $payment)
    {
        $this->authorize('delete', $payment);

        $payment->delete();

        return response()->noContent();
    }
}
