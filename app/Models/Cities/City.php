<?php

namespace App\Models\Cities;

use App\Models\Cities\Region;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'ip_start', 'ip_end', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
