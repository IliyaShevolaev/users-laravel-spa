<?php

namespace App\Models\Export;

use Illuminate\Database\Eloquent\Model;

class UserExport extends Model
{
    protected $fillable = ['user_id', 'file_name', 'file_type', 'is_user_downloaded'];
}
