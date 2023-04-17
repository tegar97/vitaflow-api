<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class foodServingUnit extends Model
{
    use HasFactory;
    //fillable with migration data
    protected $fillable = [
        'name'
    ];

}
