<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_id',
        'is_active',
        'is_base',
        'base_recipe_id',
    ];

    // 🔹 العلاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 🔹 العلاقة مع المكونات
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
                    ->withPivot('quantity_needed')
                    ->withTimestamps();
    }

    // 🔹 الوصفة الأساسية (لو موجودة)
    public function baseRecipe()
    {
        return $this->belongsTo(Recipe::class, 'base_recipe_id');
    }

    // 🔹 الوصفات اللي مبنية على الوصفة دي
    public function childRecipes()
    {
        return $this->hasMany(Recipe::class, 'base_recipe_id');
    }
}
