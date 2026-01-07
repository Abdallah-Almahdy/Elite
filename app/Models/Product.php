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
    protected $casts = [
        'uses_recipe' => 'boolean',
        'is_stock' => 'boolean',
        'is_weight' => 'boolean',
    ];
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
        'uses_recipe',
        'is_stock',
        'is_weight',
    ];

    public function defaultWarehouse()
    {
        return $this->belongsToMany(Warehouse::class,'warehouse_products','product_id','warehouse_id')
        ->withPivot(['quantity'])
        ->where('warehouses.is_default', true);
    }

    public function promoCodes()
    {
        return $this->belongsToMany(PromoCode::class, 'promo_code_products', 'product_id', 'promo_code_id');
    }

    public function warehouseProducts()

    {
        return $this->hasMany(WarehouseProduct::class);
    }


    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class);

    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(SubSection::class, 'section_id', 'id');
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }




    public function favorites(): HasMany
    {
        return $this->hasMany(Favorit::class);
    }


    public function options()
    {
        return $this->hasMany(Options::class, 'product_id');
    }


    public function addsOn()
    {
        return $this->hasMany(AddsOn::class);
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


    public function units()
    {
        return $this->belongsToMany(Unit::class, 'product_units', 'product_id', 'unit_id')
            ->using(ProductUnits::class)
            ->withPivot('conversion_factor', 'price', 'sallprice', 'is_base')
            ->withTimestamps();
    }

}
