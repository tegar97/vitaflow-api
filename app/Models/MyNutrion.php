<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyNutrion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'program_id',
        'date',
        'targetCalories',
        'calorieLeft',
        'activityCalories',
        'carbohydrate',
        'protein',
        'fat',
        'intakeCalories',




        'status',
    ];
}
