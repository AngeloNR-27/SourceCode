<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_KEY'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Product Name',
                            ],
                            'unit_amount' => 1000, // Amount in cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => 'https://example.com/success',
                'cancel_url' => 'https://example.com/cancel',
            ]);

            // Redirection vers la page de paiement Stripe
            return redirect($session->url);
        } catch (ApiErrorException $e) {
            // Gestion des erreurs de l'API Stripe
            return back()->withError($e->getMessage());
        }
    }
}
