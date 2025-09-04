<?php

namespace App\Models\Cities;

use App\Models\Cities\City;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
