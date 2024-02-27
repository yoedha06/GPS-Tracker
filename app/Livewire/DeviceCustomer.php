<?php

namespace App\Livewire;

use App\Models\Device;
use Livewire\Component;
use Livewire\WithPagination;

class DeviceCustomer extends Component
{
    use WithPagination;

    public $search;

    public $queryString = [
        'search' => ['except' => ''],
    ];

    public function render()
    {
        return view('livewire.device-customer', [
            'device' => Device::when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            })->paginate(10),
        ]);
    }
    
    
}
