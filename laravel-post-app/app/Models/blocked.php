<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blocked extends Model
{
    use HasFactory;

    protected $table = 'blocked';

    public $timestamps = false;

    protected $fillable = ['blocker','blocked'];
}
