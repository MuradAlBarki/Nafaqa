<?php

namespace App\Models;

use App\EpaymentStatusEnum;
use App\PaymentStatusEnum;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['divorce_case_id','obligation_id','amount','payment_date', 'payment_for_date', 'proof_document_url','status'];

    protected $casts = [
        'status' => PaymentStatusEnum::class,
        'payment_date' => 'date',
        'payment_for_date' => 'date',
    ];

    public function divorceCase()
    {
        return $this->belongsTo(DivorceCase::class);
    }

    public function epayments()
    {
        return $this->hasMany(Epayment::class);
    }

      public function epayment()
    {
        return $this->epayments()
            ->where('status', EpaymentStatusEnum::Success->value)
            ->latest()
            ->first();
    }

    public function getEpaidAttribute(): bool
    {
        return $this->epayments()
            ->where('status', EpaymentStatusEnum::Success->value)
            ->exists();
    }
    
}
