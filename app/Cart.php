<?php

namespace App;

class Cart
{
    public $products = null;
    public $count = 0;
    public $total = 0;

    public function __construct($existingOrder){
    	if($existingOrder){
    		$this->products = $existingOrder->products;
    		$this->count = $existingOrder->count;
    		$this->total = $existingOrder->total;
    	}
    }

    public function add($product, $quantity){
    	$orderContent = ['quantity' => 0, 'price' => 0, 'product'=> $product];

    	//checking if there is already a product
    	if($this->products){
    		//checking if the item to be added exists on products
    		if(array_key_exists($product->id, $this->products)){
	            $orderContent = $this->products[$product->id];
                if($orderContent['quantity'] + $quantity > $product->stock){
                    return false;
                }
                $orderContent['quantity'] += $quantity;
                $orderContent['price'] += $product->price * $quantity;
                $this->total += $product->price * $quantity;
	        } else {
                $orderContent['price'] = $product->price * $quantity;
                $orderContent['quantity'] += $quantity;
                $this->total += $orderContent['price'];
            }
            $this->products[$product->id] = $orderContent;
            $this->count = count($this->products);
    	} else {
            $orderContent['quantity'] += $quantity;
            $orderContent['price'] = $product->price * $quantity;
            $this->total += $orderContent['price'];
            $this->products[$product->id] = $orderContent;
            $this->count = count($this->products);
        }
    }

    public function remove($id){
        $toBeRemoved = $this->products[$id];
        $this->total -= $toBeRemoved['price'];
        unset($this->products[$id]);
        $this->count = count($this->products);
    }
}
