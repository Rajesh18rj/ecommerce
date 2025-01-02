<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;


#[Title('Products - RJ Webshop')]
class ProductsPage extends Component
{
    use WithPagination;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured;

    #[Url]
    public $on_sale;

    #[Url]
    public $price_range = 300000;


    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);

        // selected Categories filter
        if(!empty($this->selected_categories)){
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        // selected Brands filter
        if(!empty($this->selected_brands)){
            $productQuery->whereIn('brand_id', $this->selected_brands);
        }

        // Product Status filter -  Featured Products
        if($this->featured){
            $productQuery->where('is_featured', 1);
        }

        //Product Status filter - On Sale
        if($this->on_sale){
            $productQuery->where('on_sale', 1);
        }

        //this for price range filter
        if($this->price_range){
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }


        return view('livewire.products-page',
        [
            'products' => $productQuery->paginate(9),

            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),


        ]);
    }
}
