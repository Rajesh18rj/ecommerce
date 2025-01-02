<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - RJ Webshop ')]

class HomePage extends Component
{

    public function render()
    {
        $brands = Brand::where('is_active',1)->get();  //we are only getting active brands so that why is_active 1, it doesnt get the inactive brands

        $categories = Category::where('is_active',1)->get();

        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories
        ]);
    }
}
