<?php

namespace App\Livewire\Search;

use App\Models\Product;
use Illuminate\Http\Request;
use Livewire\Component;

class Search extends Component
{

    public $searchText;
    public $data;


    public function makeSearch(Request $request)
    {


        $this->data = Product::where('name', 'like', '%' . $this->searchText . '%')->get();
    }

    public function render()
    {
        return view('livewire.search.search');
    }
}
