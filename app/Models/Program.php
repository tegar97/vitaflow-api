<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name',
        'program_description',
        'program_duration',
        'program_goal_weight',
        'program_type',
        'image',
        'bmi_min',
        'bmi_max',


    ];
}
