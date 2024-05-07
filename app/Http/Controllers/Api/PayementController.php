<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEvenementRequest;
use App\Http\Requests\EditEvenementRequest;
use App\Models\Evenement;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $YOUR_DOMAIN = 'http://localhost:5173/';
            // Configurez la clé secrète de Stripe
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $lineItems = [[
                'price' => "123",
                'quantity' => 1,
            ]];

            // Créez la session de paiement avec Stripe

            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => "maheryrak1234@gmail",
                'line_items' => $lineItems,
                'mode' => 'subscription',
                'subscription_data' => [
                    'trial_from_plan' => true,
                ],
                'success_url' => $YOUR_DOMAIN . "success",
                'cancel_url' => $YOUR_DOMAIN . "cancel"
            ]);
            // Retournez l'URL de la session de paiement au frontend
            return response()->json(['url' => $checkout_session->url]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error server' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function hello(Request $request)
    {
        return response()->json(['hello' => "hello wolrd"]);
    }
}
