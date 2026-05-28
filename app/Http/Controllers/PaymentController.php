<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Auth; // ទាញយកជំនួយសម្រាប់ពិនិត្យ Auth

class PaymentController extends Controller
{
    // ទំព័រ Checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);

        // បើ Cart ទទេ ត្រឡប់ទៅទំព័រ Cart
        if (empty($cart)) {
            return redirect()->route('cart.index')
                             ->with('error', 'Cart is empty!');
        }

        // គណនាតម្លៃសរុប
        $total = array_sum(
            array_map(fn($item) => $item['price'] * $item['quantity'], $cart)
        );

        // ទាញយកទិន្នន័យ User (បើមាន) ដើម្បីដោះស្រាយបញ្ហា Guest
        $user = Auth::user();

        return view('frontend.checkout', compact('cart', 'total', 'user'));
    }

    // @ឡាក់ បង់ប្រាក់ជាសាច់ប្រាក់ (Cash on Delivery)
    public function cashOnDelivery(Request $request)
    {
        // ពិនិត្យ Input
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // បង្កើត Order
        $order = $this->createOrder($request, 'cash');

        // លុប Cart
        session()->forget('cart');

        return redirect()
            ->route('payment.success', $order->order_number)
            ->with('success', 'Order placed successfully!');
    }

    // 💳 បង់ប្រាក់តាម Stripe (Credit Card)
    public function stripePayment(Request $request)
    {
        // ពិនិត្យ Input
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string',
            'stripeToken' => 'required',
        ]);

        try {
            // ភ្ជាប់ Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            $cart  = session()->get('cart', []);
            $total = array_sum(
                array_map(fn($item) => $item['price'] * $item['quantity'], $cart)
            );

            // 💡 ដំណោះស្រាយ៖ បើជា Guest ឱ្យដាក់ពិពណ៌នាថា "Guest Customer" ដើម្បីកុំឱ្យបុក Error
            $description = Auth::check() ? 'Order Payment - ' . Auth::user()->name : 'Order Payment - Guest (' . $request->name . ')';

            // ដកប្រាក់ពី Stripe
            $charge = Charge::create([
                'amount'      => $total * 100, // គិតជា cents
                'currency'    => 'usd',
                'source'      => $request->stripeToken,
                'description' => $description,
            ]);

            // បង្កើត Order
            $order = $this->createOrder($request, 'stripe', $charge->id);

            // លុប Cart
            session()->forget('cart');

            return redirect()
                ->route('payment.success', $order->order_number)
                ->with('success', 'Payment successful!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    // ទំព័របង្ហាញ Order ជោគជ័យ
    public function success($orderNumber)
    {
        $order = Order::with('items.product')
                      ->where('order_number', $orderNumber)
                      ->firstOrFail();

        return view('frontend.payment-success', compact('order'));
    }

    // Function ជំនួយ: បង្កើត Order + Order Items
    private function createOrder(Request $request, string $method, string $paymentId = null): Order
    {
        $cart  = session()->get('cart', []);
        $total = array_sum(
            array_map(fn($item) => $item['price'] * $item['quantity'], $cart)
        );

        // រក្សាទុក Order ក្នុង Database
        $order = Order::create([
            'order_number'   => 'ORD-' . strtoupper(uniqid()),
            'user_id'        => Auth::id(), 
            'total_amount'   => $total,
            'status'         => $method === 'cash' ? 'pending' : 'paid',
            'payment_method' => $method,
            'payment_id'     => $paymentId,
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address'        => $request->address,
        ]);

        // រក្សាទុក Order Items
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
        }

        return $order;
    }
}