<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    protected $fillable = ['name', 'file_name', 'user_id'];
}
