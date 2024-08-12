<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $table = 'user';

    public $timestamps = false;

    protected $fillable = ['id', 'name', 'email', 'pwd', 'bio', 'image_path', 'cover_img_path'];
}
