<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Response;
use Inertia\Inertia;

class PaymentHandlerController extends Controller
{
    /**
     * PaymentRequest Handler pay method.
     *
     * @return Response
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function pay(): Response
    {
        // Response params.
        $base = secure_url('/');
        $manifest = '<' . $base . '/payment-manifest.json>; rel="payment-method-manifest"';

        // Return the response.
        return response(null, 204)
            ->header('Link', $manifest);
    }

    /**
     * Return the checkout page for the payment handler.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function checkout()
    {
        return Inertia::render('API/Pay/Checkout');
    }
}
