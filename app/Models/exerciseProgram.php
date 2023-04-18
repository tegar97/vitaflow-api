<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exerciseProgram extends Model
{
    use HasFactory;


    protected $fillable = [
        'program_name',
        'program_goal',
        'program_duration',
        'program_difficulty',
    ];
}
