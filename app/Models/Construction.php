<?php

// app/Models/Construction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construction extends Model
{
    use HasFactory;

    protected $fillable = [
    'name', 'description', 'meal_recommendation', 'exercise_recommendation', 'lifestyle_recommendation', 'meal_vegetables', 'meal_fruits', 'meal_fish_meat', 'meal_seasonings', 'meal_dried_goods', 'meal_tea','exercise', 'lifestyle'
];
}