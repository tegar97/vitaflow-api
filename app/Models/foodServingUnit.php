<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class foodServingUnit extends Model
{
    use HasFactory;

    protected $table = 'food_serving_units';
    //fillable with migration data
    protected $fillable = [
        'name',
        'serving_size',
    ];

    public function food()
    {
        return $this->belongsToMany(Food::class);
    }

    // with convert serving unit
    public function convertServingUnit()
    {
        return $this->hasMany(convertFoodUnit::class);
    }

}
