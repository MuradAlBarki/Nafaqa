<?php

namespace App\Models;

use App\EpaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'gateway',
        'status',
        'response_json',
    ];

     protected $casts = [
        'status'        => EpaymentStatusEnum::class,
        'response_json' => 'array',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
