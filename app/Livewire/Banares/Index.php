<?php

namespace App\Livewire\Banares;

use App\Models\Banar;
use Livewire\Component;

class Index extends Component
{
    public function delete($id)
    {
        Banar::find($id)->delete();
    }
    public function render()
    {


        $data = Banar::all();
        return view('livewire.banares.index', ['data' => $data]);
    }
}
