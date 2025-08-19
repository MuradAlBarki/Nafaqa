<?php

namespace App\Models;

use App\EpaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Epayment extends Model
{
    use HasFactory, LogsActivity;

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

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('epayment')  
            ->logAll()                 
            ->dontSubmitEmptyLogs(false);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "{$eventName} on Epayment #{$this->id}";
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
