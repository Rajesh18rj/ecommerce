<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $guarded=[];

    // Address belongs to Order
    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function getFullNameAttribute(){
        return '($this->first_name) ($this->last_name)';
    }
}
