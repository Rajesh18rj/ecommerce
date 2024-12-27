<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Brand;
use App\Models\OrderItem;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts =[
        'images' => 'array'
    ];

    # mainly we define product relationship of category and brand becoz we use this both in product table as a ForeignId

    //Product belongs to Category
    public function category(){
        return $this->belongsTo(Category::class);
    }

    //Product Belongs to Brand
    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    //Product belongs to OrderItem

    public function orderItem(){
        return $this->hasMany(OrderItem::class);
    }
}
