<?php

namespace App\Models;


use
    Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class  Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'name',
        'price',
        'description',
        'photo',
        'section_id',
        'company_id',
        'active',
        'qnt',
        'purchase_count',
        'offer_rate',
        'bar_code',
    ];


    // public function section(): BelongsTo
    // {
    //     return $this->belongsTo(SubSection::class);
    // }
    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
    public function subSection(): BelongsTo
    {
        return $this->belongsTo(SubSection::class, 'section_id', 'id');
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }
    public function orderProduct(): BelongsTo
    {
        return $this->belongsTo(OrderProduct::class);
    }
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorit::class);
    }

    public function options()
    {
        return $this->belongsToMany(Options::class, 'product_options', 'product_id', 'option_id');
    }

    public function addsOn()
    {
        return $this->belongsToMany(AddsOn::class, 'product_adds_on', 'product_id', 'adds_on_id');
    }

    public function recipeOverrides()
    {
        return $this->hasMany(ProductRecipeOverride::class);
    }

    public function recipe()
    {
        return $this->hasOne(Recipe::class, 'product_id');
    }

    public function hasRecipe()
    {
        return $this->uses_recipe;
    }

}
