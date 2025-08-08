<?php
namespace App\Models;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DivorceCase extends Model
{
    use HasFactory, SoftDeletes;

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
}

