<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $table = 'messages';

    public $timestamps = false;

    protected $fillable = ['text','sender', 'receiver', 'status', 'year', 'month', 'day', 'hour', 'minute'];
}
