<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusRumah extends Model
{
    use HasFactory;

    protected $table = 'status_rumah';
    protected $fillable = ['status'];
}
