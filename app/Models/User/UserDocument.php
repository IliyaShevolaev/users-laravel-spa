<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    protected $fillable = ['file_name', 'user_id'];
}
