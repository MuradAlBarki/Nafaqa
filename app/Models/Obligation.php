<?php

namespace App\Models;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obligation extends Model
{
    use HasFactory;

    protected $fillable = ['divorce_case_id','amount','start_date','end_date','status'];

    protected $casts = [
        'status' => StatusEnum::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function divorceCase()
    {
        return $this->belongsTo(DivorceCase::class);
    }
}
