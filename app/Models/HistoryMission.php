<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryMission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'program_id',
        'mission_id',
        'date',
        'status',
    ];

}
