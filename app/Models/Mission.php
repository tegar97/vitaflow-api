<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'name',
        'description',
        'icon',
        'color_Theme',
        'point',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function myMission()
    {
        return $this->hasMany(MyMission::class);
    }
}
