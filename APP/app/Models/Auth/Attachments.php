<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    protected $fillable = ['file_name', 'file_size', 'path_name'];
}
