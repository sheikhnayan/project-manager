<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Subscription;
use Carbon\Carbon;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        dd($request->all());
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\Exception $e) {
            return response('Webhook Error: ' . $e->getMessage(), 400);
        }

        if ($event->type === 'customer.subscription.created' || $event->type === 'customer.subscription.updated') {
            $subscription = $event->data->object;
            $stripeCustomerId = $subscription->customer;
            $currentPeriodEnd = Carbon::createFromTimestamp($subscription->current_period_end)->toDateString();

            // Retrieve Stripe customer details
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $customer = $stripe->customers->retrieve($stripeCustomerId, []);

            // Find or create company by Stripe customer ID
            $company = Company::where('stripe_customer_id', $stripeCustomerId)->first();
            if (!$company) {
                $company = Company::create([
                    'name' => $customer->description ?? 'Unknown',
                    'email' => $customer->email ?? null,
                    'stripe_customer_id' => $stripeCustomerId,
                    'is_subscribed' => true,
                    'subscription_expiry' => $currentPeriodEnd,
                ]);

                // Retrieve registration details from session
                $firstName = session('pending_first_name', 'Admin');
                $lastName = session('pending_last_name', 'User');
                $email = session('pending_email', $customer->email ?? null);
                $password = session('pending_password', str()->random(12));

                $user = \App\Models\User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'company_id' => $company->id,
                    'role' => 'admin',
                    'password' => bcrypt($password),
                ]);

                // Send confirmation email
                \Mail::send('emails.subscription_confirmation', [
                    'user' => $user,
                    'company' => $company,
                ], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Subscription Successful - Project Manager SaaS');
                });
            } else {
                $company->is_subscribed = true;
                $company->subscription_expiry = $currentPeriodEnd;
                $company->save();
            }
        }

        if ($event->type === 'customer.subscription.deleted') {
            $subscription = $event->data->object;
            $stripeCustomerId = $subscription->customer;
            $company = Company::where('stripe_customer_id', $stripeCustomerId)->first();
            if ($company) {
                $company->is_subscribed = false;
                $company->subscription_expiry = null;
                $company->save();
            }
        }

        return response('Webhook handled', 200);
    }
}
