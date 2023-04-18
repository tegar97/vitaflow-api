<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'food';
    protected $fillable = [
        'food_name',
        'food_image',
        'food_rating',
        'default_size',
        'food_serving_unit_id',
        'food_calories',
        'food_fat',
        'food_saturated_fat',
        'food_trans_fat',
        'food_cholesterol',
        'food_sodium',
        'food_carbohydrate',
        'food_fiber',
        'food_sugar',
        'food_protein',

    ];

    public function foodServingUnit()
    {
        return $this->belongsTo(foodServingUnit::class);
    }


    // with convert serving unit

    public function convertServingUnit()
    {
        return $this->hasMany(convertFoodUnit::class);
    }



}
