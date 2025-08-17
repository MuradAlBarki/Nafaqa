<?php

namespace App\Http\Controllers;

use App\Models\Epayment;
use DragonCode\Contracts\Cashier\Config\Payment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class EpaymentController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Payment $payment)
    {
        $this->authorize('viewAny', Epayment::class);

        $epayments = $payment->epayments()->paginate(15);

        return view('epayments.index', compact('epayments'));
    }

    
    public function show(Epayment $epayment, Payment $payment)
    {
        $this->authorize('view', $epayment);

        return view('epayments.show', compact('epayment'));
    }
}
