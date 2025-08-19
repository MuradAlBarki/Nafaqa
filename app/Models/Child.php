<?php

namespace App\Models;

use App\GenderEnum;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Child extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'case_id',
        'first_name',
        'nationality_no',
        'date_of_birth',
        'gender',
        'status',
    ];

      protected $casts = [
        'status' => StatusEnum::class,
        'gender' => GenderEnum::class,
        'date_of_birth' => 'date'
    ];

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('child')  
            ->logAll()                 
            ->dontSubmitEmptyLogs(false);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "{$eventName} on Child #{$this->id}";
    }

    public function divorceCase()
    {
        return $this->belongsTo(DivorceCase::class, 'case_id');
    }
}
