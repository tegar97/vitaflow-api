<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exerciseType extends Model
{
    use HasFactory;

    protected $fillable = [

        'exercise_name',
        'exercise_description',
        'exercise_video_url',
        'exercise_duration',
        'exercise_repetition',
        'calories_burned_estimate'



    ];


}
