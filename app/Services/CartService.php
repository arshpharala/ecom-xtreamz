<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CartService
{
    protected $sessionKey = 'cart';

    // Handle static calls like CartService::getTotal()
    public static function __callStatic($method, $args)
    {
        $instance = new static();

        if (method_exists($instance, $method)) {
            return $instance->$method(...$args);
        }

        throw new \BadMethodCallException("Method {$method} does not exist.");
    }


    // Get all cart items
    public function getItems()
    {
        return Session::get($this->sessionKey, []);
    }

    // Get a single item by rowId
    public function getItem($rowId)
    {
        $cart = $this->getItems();
        return $cart[$rowId] ?? null;
    }

    // Add item to cart
    public function add($rowId, $qty = 1, $price = null, array $options = [])
    {
        $cart = $this->getItems();

        if (isset($cart[$rowId])) {
            $cart[$rowId]['qty'] += $qty;
        } else {
            $cart[$rowId] = [
                'qty'     => $qty,
                'price'   => $price,
                'options' => $options,
            ];
        }

        $cart[$rowId]['subtotal'] = $cart[$rowId]['price'] * $cart[$rowId]['qty'];
        $this->save($cart);
    }

    // Update item quantity
    public function update($rowId, $qty)
    {
        $cart = $this->getItems();

        if (isset($cart[$rowId])) {
            $cart[$rowId]['qty']      = $qty;
            $cart[$rowId]['subtotal'] = $cart[$rowId]['price'] * $qty;
            $this->save($cart);
        }

        return $cart;
    }

    // Remove an item from cart
    public function remove($rowId)
    {
        $cart = $this->getItems();
        unset($cart[$rowId]);
        $this->save($cart);
        return $cart;
    }

    // Clear the entire cart
    public function clear()
    {
        Session::forget($this->sessionKey);
    }

    // Save cart to session
    public function save($cart)
    {
        Session::put($this->sessionKey, $cart);
    }

    // Count total items
    public function getItemCount()
    {
        return collect($this->getItems())->sum('qty');
    }

    // Get subtotal (total without tax/fees)
    public function getSubtotal()
    {
        $cart = $this->getItems();
        return array_sum(array_column($cart, 'subtotal'));
    }

    // Get total (can be extended for tax, shipping etc.)
    public function getTotal()
    {
        return $this->getSubtotal();
    }
}
