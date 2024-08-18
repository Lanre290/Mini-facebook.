<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deletedMessages extends Model
{
    use HasFactory;

    protected $table = 'likes';

    public $timestamps = false;

    protected $fillable = ['user','message'];
}
