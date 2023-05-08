<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class convertFoodUnit extends Model
{
    use HasFactory;

    protected $table = 'food_food_serving_unit';

    protected $fillable = [
        'food_id',
        'food_serving_unit_id',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }


    public function foodServingUnit()
    {
        return $this->belongsTo(foodServingUnit::class);
    }
}
