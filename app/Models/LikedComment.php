<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikedComment extends Model
{
    use HasFactory;

    protected $table = 'liked_comment';

    public $timestamps = false;

    protected $fillable = ['user','comment','notification'];
}
