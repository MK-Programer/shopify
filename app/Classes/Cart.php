<?php

namespace App\Classes;

class Cart {
    public $items;
    public $totalQuantity;
    public $totalPrice;

    public function __construct($prevCart){
        if($prevCart != null){
            $this->items = $prevCart->items;
            $this->totalQuantity = $prevCart->totalQuantity;
            $this->totalPrice = $prevCart->totalPrice;
        }else{
            $this->items = [];
            $this->totalQuantity = 0;
            $this->totalPrice = 0;
        }
    }

    public function addItem($id, $product){
        $price = (Double) str_replace('$', '', $product->price);

        // the item already exist
        if(array_key_exists($id, $this->items)){
            $productToAdd = $this->items[$id];
            $productToAdd['quantity']++;
            $productToAdd['totalSinglePrice'] = $productToAdd['quantity'] * $price;
        }// first time to add this product to cart
        else{
            $productToAdd = ['quantity'=> 1, 'totalSinglePrice'=> $price, 'data'=>$product];
        }

        $this->items[$id] = $productToAdd;
        $this->totalQuantity++;
        $this->totalPrice = $this->totalPrice + $price;
    }

    public function recalculatePriceAndQuantity(){
        $totalQuantity = 0;
        $totalPrice = 0;
        foreach($this->items as $item){
            $totalQuantity += $item['quantity'];
            $totalPrice += $item['totalSinglePrice'];
          
        }
        $this->totalQuantity = $totalQuantity;
        $this->totalPrice = $totalPrice;
    }

    public function deleteItem($id){
        unset($this->items[$id]); // delete this product
        $this->recalculatePriceAndQuantity();
    }

    public function decreaseByOne($id){
        // if the quantity is greater than 1 decrease
        if($this->items[$id]['quantity'] > 1){
            $this->items[$id]['quantity'] -= 1;
            $this->items[$id]['totalSinglePrice'] = $this->items[$id]['quantity'] * (Double) str_replace('$', '', $this->items[$id]['data']->price);
            $this->recalculatePriceAndQuantity();
        }
        // else{
        //     // remove this element
        //     $this->deleteItem($id);
        // }
    }


}

?>