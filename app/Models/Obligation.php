<?php

namespace App\Models;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Obligation extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['divorce_case_id','amount','start_date','end_date','status'];

    protected $casts = [
        'status' => StatusEnum::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('obligation')  
            ->logAll()                 
            ->dontSubmitEmptyLogs(false);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "{$eventName} on Obligation #{$this->id}";
    }

    public function divorceCase()
    {
        return $this->belongsTo(DivorceCase::class);
    }
}
