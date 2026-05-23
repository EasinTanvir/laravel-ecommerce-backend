<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();

        $signature = $request->header('Stripe-Signature');

        $webhookSecret = env('services.stripe.secret');

        try {

            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );

        } catch (\UnexpectedValueException $e) {

            return response()->json([
                'message' => 'Invalid Payload'
            ], Response::HTTP_BAD_REQUEST);

        } catch (SignatureVerificationException $e) {

            return response()->json([
                'message' => 'Invalid Signature'
            ], Response::HTTP_BAD_REQUEST);
        }

        switch ($event->type) {

            case 'payment_intent.succeeded':

                $paymentIntent = $event->data->object;

                $payment = Payment::where(
                    'stripe_payment_intent_id',
                    $paymentIntent->id
                )->first();

                if ($payment) {

                    $payment->update([
                        'status' => 'paid',
                        'transaction_id' => $paymentIntent->id,
                    ]);

                    $payment->order()->update([
                        'status' => 'processing'
                    ]);
                }

                break;


            case 'payment_intent.payment_failed':

                $paymentIntent = $event->data->object;

                $payment = Payment::where(
                    'stripe_payment_intent_id',
                    $paymentIntent->id
                )->first();

                if ($payment) {

                    $payment->update([
                        'status' => 'failed'
                    ]);

                    $payment->order()->update([
                        'status' => 'cancelled'
                    ]);
                }

                break;
        }

        return response()->json([
            'message' => 'Webhook Handled Successfully'
        ]);
    }
}