<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyMission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mission_id',
        'status',
        'target',
        'current',
        'type_target'
    ];
}
