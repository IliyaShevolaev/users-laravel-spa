<?php

namespace App\Models\Export;

use Illuminate\Database\Eloquent\Model;

class UserCityExport extends Model
{
    protected $fillable = ['user_id', 'file_name', 'is_user_downloaded'];
}
