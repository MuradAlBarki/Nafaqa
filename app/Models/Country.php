<?php

namespace App\Models;

use Altwaireb\Countries\Models\Country as Model;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Country extends Model
{
    use HasFactory; 


        protected $casts = [
        'status' => StatusEnum::class,
    ];
    
    public function getNameAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->arabic_name ?? $this->english_name ?? '';
        }

        return $this->english_name ?? $this->arabic_name ?? '';
    }


}
