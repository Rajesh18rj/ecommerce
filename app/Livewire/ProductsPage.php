<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;


#[Title('Products - RJ Webshop')]
class ProductsPage extends Component
{
    use LivewireAlert;

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

    #[Url]
    public $sort = 'latest';

    // add product to cart method
    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCartWithQty($product_id);

        $this->dispatch('update-cart-count', total_count : $total_count)->to(Navbar::class);

        $this->alert('success', 'Product added to the cart successfully..', [
            'position' => 'bottom-end',
            'timer' => 5000,
            'toast' => true,
        ]);
    }


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

        // sort by latest and price

        if($this->sort == 'latest') {
            $productQuery->latest();
        }

        if($this->sort == 'price'){
            $productQuery->orderBy('price');
        }

        return view('livewire.products-page',
        [
            'products' => $productQuery->paginate(9),

            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),


        ]);
    }
}
