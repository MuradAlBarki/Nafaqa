<?php

namespace App\Policies;

use App\Models\DivorceCase;
use App\PaymentStatusEnum;
use App\Models\Payment;
use App\Models\User;
use App\StatusEnum;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{

    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    public function create(User $user): bool
    {
        return $user->can('payments.create');
    }

    public function viewAny(User $user, DivorceCase $divorceCase): bool
    {
        return $user->can('payments.show') || $divorceCase->father->user_id === $user->id;
    }

    public function show(User $user, Payment $payment): bool
    {
        return $user->can('payments.show') || $payment->divorceCase->father->user_id === $user->id || $payment->divorceCase->mother->user_id === $user->id;
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        return $user->can('payments.update');
    }

    public function pay(User $user, Payment $payment): bool
    {
        return $payment->status == PaymentStatusEnum::Entry && $payment->divorceCase->father->user_id === $user->id;
    }
    public function changeStatus(User $user, Payment $payment): bool
    {
        return $user->can('payments.changeStatus') || ( $payment->status == PaymentStatusEnum::PaidNotVerified && $payment->divorceCase->mother->user_id === $user->id);
    }

    public function exportLatePayments(User $user)
    {
        return $user->can('download.reports');
    }
}
