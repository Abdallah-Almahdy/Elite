<?php

namespace App\Livewire\Sections;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Show extends Component
{
    public $data;
    public $search = '';

    public function makeSearch()
    {
        $this->data = $this->data->filter(function ($item) {
            return stripos($item->name, $this->search) !== false;
        });

    }

    #[Layout('admin.livewireLayout')]

    public function render()
    {


        return view('livewire.sections.show');
    }
}
