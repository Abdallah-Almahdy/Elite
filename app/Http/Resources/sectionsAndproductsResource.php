<?php

namespace App\Http\Resources;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Resources\productsResource;
use App\Http\Resources\sectionsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class sectionsAndproductsResource extends JsonResource
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
            'products' => productsResource::collection(
               $this->products()->where('active', 1)->with('defaultWarehouse')->get()
            ),
            

        ];
    }
}
