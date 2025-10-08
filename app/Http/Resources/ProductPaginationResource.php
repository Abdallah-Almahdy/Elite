<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductPaginationResource extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'total_products' => $this->resource->total(),
            'total_pages'    => $this->resource->lastPage(),
            'current_page'   => $this->resource->currentPage(),
            'per_page'       => $this->resource->perPage(),
            'data'           => productsResource::collection($this->resource->items()),
        ];
    }
}

