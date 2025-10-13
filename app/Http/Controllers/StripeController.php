<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class StripeController extends Controller
{
    public function subscribe(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

            $firstName = $request->input('first_name');
            $lastName = $request->input('last_name');
            $email = $request->input('email');
            $companyName = $request->input('company_name');
            $password = $request->input('password');

        // Create Stripe customer
            $customer = \Stripe\Customer::create([
                'email' => $email,
                'name' => $firstName . ' ' . $lastName,
                'description' => $companyName,
        ]);

        // Create Stripe Checkout Session for subscription
            // Store registration info in session for webhook/user creation
            session([
                'pending_stripe_customer_id' => $customer->id,
                'pending_company_name' => $companyName,
                'pending_email' => $email,
                'pending_first_name' => $firstName,
                'pending_last_name' => $lastName,
                'pending_password' => $password,
            ]);

            $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'customer' => $customer->id,
            'line_items' => [[
                'price' => env('STRIPE_PRICE_ID'),
                'quantity' => 1,
            ]],
                'success_url' => route('homepage') . '?success=true',
            'cancel_url' => route('homepage') . '?canceled=true',
        ]);

        // Optionally, store the customer info in session for later use
        session(['pending_stripe_customer_id' => $customer->id, 'pending_company_name' => $companyName, 'pending_email' => $email]);

        return redirect($session->url);
    }
}
