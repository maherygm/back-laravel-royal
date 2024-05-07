<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Invoice;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PayerController extends Controller
{
    //
    public function hello(Request $request)
    {
        return response()->json(['hello' => "hello wolrd"]);
    }
    public function stripe(Request $request)
    {
        try {
            $YOUR_DOMAIN = 'http://localhost:5173/';

            // Configurez la clé secrète de Stripe
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $lineItems = [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price' => $request->param,
                'quantity' => $request->day,
            ]];

            // Créez la session de paiement avec Stripe

            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $request->mail,
                'line_items' => $lineItems,
                'mode' => 'subscription',
                'subscription_data' => [
                    'trial_from_plan' => true,
                ],
                'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel', [], true),
            ]);
            // Retournez l'URL de la session de paiement au frontend

            $order = new Order();
            $order->status = 'unpaid';
            $order->event_types = $request->typesEvenement;
            $order->date_event = $request->dateEvenement;
            $order->total_price = $request->prix;
            $order->session_id = $checkout_session->id;
            $order->user_id = $request->user_id;
            $order->user_mail = $request->mail;
            $order->save();


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
    public function success(Request $request)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');


        try {
            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if (!$session) {
                throw new NotFoundHttpException();
            }

            $order = Order::where('session_id', $session->id)->first();


            if (!$order) {
                return redirect()->away('http://localhost:5173/redirect/cancel');
                throw new NotFoundHttpException();
            }
            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->save();
            }

            $Evenement = new Evenement();

            $Evenement->types = $order->event_types;
            $Evenement->date_evenement = $order->date_event;
            $Evenement->prix = $order->total_price;
            $Evenement->validation = 1;
            $Evenement->client_id = $order->user_id;

            $Evenement->save();


            // $invoiceId = $session->payment_intent->invoice;
            // $invoice = Invoice::retrieve($invoiceId);

            // // Vous pouvez maintenant accéder aux informations de la facture
            // $invoicePdfUrl = $invoice->invoice_pdf;

            // // $useMAil = $order->user_mail;

            // // // Par exemple, vous pouvez envoyer la facture par e-mail
            // // Mail::raw("Voici votre payement  a éte en succes  ref:120120102102", function ($message) use ($useMAil) {

            // //     $message->to($useMAil)->subject('Facuration a la location de salle ');
            // // });
            return redirect()->away('http://localhost:5173/redirect/succes');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
            return redirect()->away('http://localhost:5173/redirect/cancel');
        }
    }
    public function cancel()
    {
        return redirect()->away('http://localhost:5173/redirect/cancel');
    }
}
