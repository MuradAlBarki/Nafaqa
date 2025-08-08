<?php

namespace App\Models;

use Altwaireb\Countries\Models\Country as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory; 
    
    public function getNameAttribute(): string
{
    return app()->getLocale() === 'ar' ? $this->arabic_name : $this->english_name;
}

}
