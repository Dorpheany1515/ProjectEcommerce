<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Crucial model import

class CartController extends Controller
{
    // Display the Cart Page
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        
        return view('frontend.cart', compact('cart', 'total'));
    }

    // Add Item to Cart and Redirect to Cart Page
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'     => $product->product_name,
                'price'    => $product->sale_price,
                'image'    => $product->image,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        // Redirects directly to /cart with a success flash message
        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    // Remove Item from Cart
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    // Update Item Quantity
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
}