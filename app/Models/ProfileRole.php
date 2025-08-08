<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\StatusEnum;
use App\GenderEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ProfileRole extends Model
{
    use SoftDeletes, LogsActivity, HasFactory;

      protected $fillable = [
        'user_id',
        'nationality_id',
        'first_name',
        'mid_name',
        'last_name',
        'date_of_birth',
        'national_no',
        'IBAN',
        'document_type',
        'document_no',
        'document_file_url',
        'status',
        'gender'
    ];

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
{
    return \Spatie\Activitylog\LogOptions::defaults()->logOnlyDirty()->dontSubmitEmptyLogs();
}

    protected $casts = [
        'status' => StatusEnum::class,
        'gender' => GenderEnum::class,
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }


    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

        public function getCreatorNameAttribute()
    {

        return optional(
            $this->activities()->where('description', 'Created')->latest()->first()
        )->causer?->name;
    }

    }
