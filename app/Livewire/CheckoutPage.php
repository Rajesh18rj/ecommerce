<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Order;
use App\Models\Address;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Checkout')]
class CheckoutPage extends Component
{

    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;

    public $zip_code;
    public $payment_method;

    public function mount() {
        // if cart items is 0 , then someone access checkout page from url , we should block them , so
        $cart_items = CartManagement::getCartItemsFromCookie();
        if($cart_items == 0) {
            return redirect('/products');
        }

    }

    public function placeOrder()
    {

//        dd($this->payment_method);


        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);

        // we are going to get all cart items from cookie
        $cart_items = CartManagement::getCartItemsFromCookie();

        $line_items = [];

        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'inr',
                    'unit_amount' => $item['unit_amount'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                    ]
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'inr';
        $order->shipping_amount = 0;
        $order->shipping_method = 'none';
        $order->notes = 'Order placed by ' . auth()->user()->name;

        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;

        $redirect_url = '';

        if ($this->payment_method == 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionCheckout = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => auth()->user()->email,
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);

            $redirect_url = $sessionCheckout->url;
        } else {
            $redirect_url = route('success');

        }
        $order->save();
        $address->order_id = $order->id;
        $address->save();


//        $order->items()->createMany($cart_items);
//        CartManagement::clearCartItems();
//        Mail::to(request()->user())->send(new OrderPlaced($order)); //we creating this OrderPlaced class in Mail Directory
//        return redirect($redirect_url);

         // Filter cart items to remove 'name' before saving
    $filtered_cart_items = array_map(function ($item) {
        return [
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unit_amount' => $item['unit_amount'],
            'total_amount' => $item['total_amount'],
            // Exclude 'name' from the database insertion
        ];
    }, $cart_items);

    // Save the order items
    $order->items()->createMany($filtered_cart_items);

    // Clear cart items and redirect
    CartManagement::clearCartItems();
    Mail::to(request()->user())->send(new OrderPlaced($order)); //we creating this OrderPlaced class in Mail Directory
    return redirect($redirect_url);
//    }
    }
    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}
