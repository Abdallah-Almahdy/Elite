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
            'prod_id' => $this->id,
            'id' => $this->id,
            'name' => $this->name,
            'detail' => $this->description,
            // 'options' =>  $this->options,
            'options' => OptionsResource::collection($this->options),
            'addsOn' =>  AddsOnsResource::collection($this->addsOn),
            'price' => $this->price,
            'image' => config('app.img_base_link') . $this->photo,
            'product_quantity' => $this->qnt,
            'nutritionWeight' => 0,
            'cat_id' => $this->section_id,
            'unit_name' => 'ج.م',
            'purchase_count' => $this->purchase_count,
            'offer_rate' => $this->offer_rate,
            'bar_code' => $this->bar_code,
        ];
    }
}
