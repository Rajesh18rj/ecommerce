<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Order;

#[Title('Success - RJ WebShop')]
class SuccessPage extends Component
{
    public function render()
    {

        $latest_order = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();

        return view('livewire.success-page', [
            'order' => $latest_order,
        ]);
    }
}
