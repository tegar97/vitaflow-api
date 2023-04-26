<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyDrinkActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'my_mission_id',
        'value',
        'date',

    ];


}
