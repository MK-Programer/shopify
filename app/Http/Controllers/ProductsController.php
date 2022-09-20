<?php

namespace App\Http\Controllers;

use App\Classes\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    public function index(){
        // Session::remove('cart');
        // Session::remove('payment_info');
       $products = Product::paginate(3);
        return view('index', compact('products'));
    }

    public function addProductToCart($id){
        // get the previous cart from the session
        $prevCart = Session::get('cart');
        $cart = new Cart($prevCart);

        $product = Product::find($id);
        $cart->addItem($id, $product);

        Session::put('cart', $cart);
        // dump($cart);
        return redirect()->route('/');
    }

    public function showCart(){
        $cart = Session::get('cart');
        // dump($cart);
        // cart is not empty
        if($cart){
            return view('cart', ['cartItems'=>$cart]);
        }else{
            return redirect()->route('/');
        }
    }

    public function deleteProductFromCart($id){
        
        $prevCart = Session::get('cart');
        $cart = new Cart($prevCart);
        $cart->deleteItem($id);
        // update the session
        Session::put('cart', $cart);
        return redirect()->route('cart');
    }

    public function menProducts(){
        $products = Product::where('type', 'Men')->paginate(3);
        return view('index', ['products'=>$products]);
    }

    public function womenProducts(){
        $products = Product::where('type', 'Women')->paginate(3);
        return view('index', ['products'=>$products]);
    }

    public function search(Request $request){
        $searchText = $request->get('searchText');
        $products = Product::where('name', 'LIKE', $searchText."%")->paginate(3);
        return view('index', ['products'=>$products]);
    }

    public function increaseSingleProduct($id){
        $prevCart = Session::get('cart');
        $cart = new Cart($prevCart);
        $product = Product::find($id);
        $cart->addItem($id, $product);
        Session::put('cart', $cart);
        return redirect()->back();
    }

    public function decreaseSingleProduct($id){
        $prevCart = Session::get('cart');
        $cart = new Cart($prevCart);
        $cart->decreaseByOne($id);
        Session::put('cart', $cart);
        return redirect()->back();
    }

    public function createOrder(Request $request){
        
        $cart = Session::get('cart');

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $zip = $request->input('zip');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $address = $request->input('address');
        
        // cart isnot empty
        if($cart){
            $date = date('Y-m-d H:i:s');
            $newOrderArray = array('status'=>'on_hold', 'date'=>$date, 'del_date'=>$date, 'price'=>$cart->totalPrice, 
                'first_name'=>$first_name, 'last_name'=>$last_name, 'zip'=>$zip, 'phone'=>$phone, 'email'=>$email, 'address'=>$address
            );
            $created_order = DB::table('orders')->insert($newOrderArray);
            $order_id = DB::getPdo()->lastInsertId();

            foreach($cart->items as $cart_item){
                $item_id = $cart_item['data']->id;
                $item_name = $cart_item['data']->name;
                $item_price = (Double) str_replace('$','',$cart_item['data']->price);
                $newItemsInCurrentOrder = array('item_id'=>$item_id, 'order_id'=>$order_id, 'item_name'=>$item_name, 'item_price'=>$item_price);
                $created_order_items = DB::table('order_items')->insert($newItemsInCurrentOrder);
            }
            // delete cart
            Session::forget('cart');
            Session::flush();
            $payment_info = $newOrderArray;
            $payment_info['order_id'] = $order_id;
            $request->session()->put('payment_info', $payment_info);
            // redirect back with
            return redirect()->route('showPaymentPage')->withsuccess('Thanks for choosing us');
        }else{
            return redirect()->route('/');
        }
    }

    public function checkoutProducts(){
        $cart = Session::get('cart');
        // dump($cart);
        // cart is not empty
        if($cart){
            return view('checkout', ['cartItems'=>$cart]);
        }else{
            return redirect()->route('/');
        }
    }


}
