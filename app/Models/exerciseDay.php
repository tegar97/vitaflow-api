<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exerciseDay extends Model
{
    use HasFactory;

    protected $fillable = [
       'exercise_type_id',
        'workout_day_id',
         'exercise_order'
    ];


}
