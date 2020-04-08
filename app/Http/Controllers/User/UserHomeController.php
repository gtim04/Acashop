<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\CheckoutOrder;
use App\Product;
use App\Cart;
use App\Order;
use App\Category;

class UserHomeController extends Controller
{
    public function index()
    {
        if(request('category')){
            $data = Category::with('product')->where('category', request('category'))->firstOrFail()->product;
        } else {
            $data = Product::where('isDeleted', 0)->paginate(3);
        }
        $orderCount = session()->has('orders') ? session('orders')->count : 0;
        return view('user.home', compact('data', 'orderCount'));
    }

    public function addProduct(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1|lte:'.$product->stock
        ]);

        //checking if there is an existing order
        $existingOrder = session()->has('orders') ? session()->get('orders') : null;
        //initiate car container
        $order = new Cart($existingOrder);

        if($order->add($product, $request->quantity) === false){
            return response()->json(['error' => 'Sorry you cannot add more than our stock'], 500);
        } else {
            session(['orders' => $order]);
            return route('user.home');
        }
    }

    public function removeProduct(Product $product)
    {
        //checking if there is an existing order
        $existingOrder = session()->has('orders') ? session()->get('orders') : null;
        //initiate car container
        $order = new Cart($existingOrder);
        //adding product to cart
        $order->remove($product->id);
        //storing to session if there is a product
        empty($order->products) ? session()->forget('orders') : session(['orders' => $order]);
        //redirecting to home
        return route('user.home');
    }

    public function viewCart()
    {
        return session()->all();
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function checkOut(){
        if(session()->has('orders')){
            //creating orders table
            $order = Order::create([
                'code' => 'ACS-'.time().''.strtoupper(Str::random(10)),
                'user_id' => auth()->user()->id,
                'total' => session('orders')->total
            ]);

            foreach (session('orders')->products as $key => $value)
            {
                // decrement stock
                Product::where('id', $key)->decrement('stock', session('orders')->products[$key]['quantity']);
                //attaching values to linking table
                $order->product()->attach($key, ['quantity' => session('orders')->products[$key]['quantity'] , 'totalProduct' => session('orders')->products[$key]['price'] ]);
            }
            //fire event
             CheckoutOrder::dispatch($order);
            //forget the session
            session()->forget('orders');
            //route to summary
            return route('user.summary', $order->id);
        } else {
            return response()->json(['error' => 'No items to checkout!'], 500);
        }   
    }
}
