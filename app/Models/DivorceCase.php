<?php
namespace App\Models;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DivorceCase extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'mother_id',
        'father_id',
        'case_no',
        'divorce_date',
        'court_document',
        'status',
    ];

      protected $casts = [
        'status' => StatusEnum::class,
        'divorce_date' => 'date'
    ];

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('divorce_case')  
            ->logAll()                 
            ->dontSubmitEmptyLogs(false);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "{$eventName} on DivorceCase #{$this->id}";
    }
    public function children()
    {
        return $this->hasMany(Child::class, 'case_id');
    }

    public function mother()
    {
        return $this->belongsTo(ProfileRole::class, 'mother_id');
    }

    public function father()
    {
        return $this->belongsTo(ProfileRole::class, 'father_id');
    }

     public function obligation()
    {
        return $this->hasOne(Obligation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

        public function isFather($user): bool
    {
        return $this->father?->user_id === $user->id;
    }

    public function isMother($user): bool
    {
        return $this->mother?->user_id === $user->id;
    }
}

