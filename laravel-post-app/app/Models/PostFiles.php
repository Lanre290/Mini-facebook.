<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFiles extends Model
{
    use HasFactory;

    protected $table = 'post_files';

    public $timestamps = false;

    protected $fillable = ['post_id','path'];
}
