<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'user:id,name,email',
            'payment',
            'products:id,name,price'
        ])
        ->latest()
        ->paginate(10);

        return response()->json([
            'message' => 'Order List',
            'data' => OrderResource::collection($orders),
        ]);
    }

    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        $totalAmount = 0;

        $productsData = [];

        foreach ($validated['products'] as $item) {

            $product = Product::findOrFail($item['product_id']);

            $subtotal = $product->price * $item['quantity'];

            $totalAmount += $subtotal;

            $productsData[$product->id] = [
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];
        }

        Stripe::setApiKey(config('services.stripe.secret'));

      $paymentIntent = PaymentIntent::create([
    'amount' => $totalAmount * 100,
    'currency' => 'usd',
    'automatic_payment_methods' => [
        'enabled' => true,
            ],
       ]);

        $order = Order::create([
            'user_id' => $request->user()->id,
            'total_amount' => $totalAmount,
            'shipping_address' => $validated['shipping_address'],
            'status' => 'pending',
        ]);

        $order->products()->attach($productsData);

        $order->payment()->create([
            'amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'stripe_payment_intent_id' => $paymentIntent->id,
        ]);

        $order->load([
            'user:id,name,email',
            'payment',
            'products:id,name,price'
        ]);

        return response()->json([
            'message' => 'Order Created Successfully',
            'data' => new OrderResource($order),
            'client_secret' => $paymentIntent->client_secret,
        ], 201);
    }

    public function show(Order $order)
    {
        $order->load([
            'user:id,name,email',
            'payment',
            'products:id,name,price'
        ]);

        return response()->json([
            'message' => 'Order Found',
            'data' => new OrderResource($order)
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,processing,completed,cancelled'
            ]
        ]);

        $order->update([
            'status' => $validated['status']
        ]);

        return response()->json([
            'message' => 'Order Updated Successfully',
            'data' => new OrderResource($order)
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'message' => 'Order Deleted Successfully'
        ]);
    }
}