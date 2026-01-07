<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\AddsOnsResource;
use App\Http\Resources\OptionsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class productsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => config('app.img_base_link') . $this->photo,
            'quantity' =>  $this->defaultWarehouse->first()->pivot->quantity ?? 0,
            'nutritionWeight' => 0,
            'unit_name' => 'ج.م',
            'purchase_count' => $this->purchase_count,
            'offer_rate' => $this->offer_rate,
            'uses_recipe' => $this->uses_recipe,
            'active' => $this->active,
            'is_stock' => $this->is_stock,
            'is_weight' => $this->is_weight,
            'company' => $this->company->name ?? null,
            'section' => $this->section->name ?? null,
            'number'=>1,
            'Units' => ProductUnitResource::collection($this->units),
            'options' => OptionsResource::collection($this->options),
            'addsOn' =>  AddsOnsResource::collection($this->addsOn),

        ];
    }
}
