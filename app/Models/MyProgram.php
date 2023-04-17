<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyProgram extends Model
{
    use HasFactory;
    protected $fillable = [
            'user_id',
            'program_id',
            'start_date',
            'end_date',
            'status',
    ];
}
