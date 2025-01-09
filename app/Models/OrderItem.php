<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;

    protected $guarded =[];


    // This Order Item Belongs to order

    public function order(){
        return $this->belongsTo(Order::class);
    }

    // This Order Item Belongs to Product

    public function product(){
        return $this->belongsTo(Product::class);
    }


}
