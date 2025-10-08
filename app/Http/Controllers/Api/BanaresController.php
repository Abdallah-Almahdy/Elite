<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\banaresResource;
use App\Models\Banar;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BanaresController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return  banaresResource::collection(Banar::all());
    }
}
