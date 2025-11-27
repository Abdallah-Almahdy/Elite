<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->pivot->refresh();

       return [
        'id' => $this->id,
        'name' => $this->name,
        'sallprice' => $this->pivot->sallprice,
        'barcodes' => $this->pivot->barcodes->map(function($barcode){
            return [
                'id' => $barcode->id,
                'code' => $barcode->code
            ];
        }) ?? [],
        'component' => $this->pivot->components ?? []
       ];
    }
}
