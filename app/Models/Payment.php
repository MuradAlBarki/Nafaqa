<?php

namespace App\Models;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['divorce_case_id','obligation_id','amount','payment_date','proof_document_url','status'];

    protected $casts = [
        'status' => StatusEnum::class,
        'payment_date' => 'date',
    ];

    public function divorceCase()
    {
        return $this->belongsTo(DivorceCase::class);
    }
    
}
