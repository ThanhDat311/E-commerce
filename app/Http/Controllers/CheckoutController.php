<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest; // You need to create this Request class
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CheckoutController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function show()
    {
        return view('checkout');
    }

    public function process(CheckoutRequest $request)
    {
        try {
            // Prepare customer data from request
            $customerData = $request->validated();
            
            // Get User ID if authenticated
            $userId = Auth::id();

            // Call the Service
            $order = $this->orderService->processCheckout($customerData, $userId);

            // Redirect to Success Page
            return redirect()->route('checkout.success')->with('order_id', $order->id);

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function success()
    {
        if (!session('order_id')) {
            return redirect()->route('home');
        }
        return view('order-success');
    }
}