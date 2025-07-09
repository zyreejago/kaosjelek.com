<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    public static function add($productId, $quantity = 1)
    {
        $cart = self::getCart();
        $product = Product::find($productId);
        
        if (!$product) {
            return false;
        }
        
        $productKey = 'product_' . $productId;
        
        if (isset($cart[$productKey])) {
            $cart[$productKey]['quantity'] += $quantity;
        } else {
            $cart[$productKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->sale_price ?? $product->price,
                'original_price' => $product->price,
                'image' => $product->images[0] ?? null,
                'quantity' => $quantity,
                'sku' => $product->sku
            ];
        }
        
        Session::put('cart', $cart);
        return true;
    }
    
    public static function remove($productId)
    {
        $cart = self::getCart();
        $productKey = 'product_' . $productId;
        
        if (isset($cart[$productKey])) {
            unset($cart[$productKey]);
            Session::put('cart', $cart);
        }
    }
    
    public static function updateQuantity($productId, $quantity)
    {
        $cart = self::getCart();
        $productKey = 'product_' . $productId;
        
        if (isset($cart[$productKey])) {
            if ($quantity <= 0) {
                unset($cart[$productKey]);
            } else {
                $cart[$productKey]['quantity'] = $quantity;
            }
            Session::put('cart', $cart);
        }
    }
    
    public static function getCart()
    {
        return Session::get('cart', []);
    }
    
    public static function getCount()
    {
        $cart = self::getCart();
        return array_sum(array_column($cart, 'quantity'));
    }
    
    public static function getTotal()
    {
        $cart = self::getCart();
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
    
    public static function clearCart()
    {
        session()->forget('cart');
    }
}