<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Address;


class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $guarded =[];

    // We use User Table in Order Table so define that relationship between order and user

    #one order belongs to one user , (then User has Many Multiple Orders)

    public function user(){
        return $this->belongsTo(User::class);
    }

    //This order has multiple Items

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    //Then Order also have One Address

    public function address(){
        return $this->hasOne(Address::class);
    }
}
