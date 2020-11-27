<?php

namespace App\Http\Controllers\Pay\C2B;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MPesaController extends Controller
{
    protected $baseURI;
    protected $headers;

    public function __construct()
    {
        // Get the base URI and headers
        $this->uri = env('AMPREST_PAYMENT_API_URI');
        $this->headers = [
            'Api-Token' => env('AMPREST_PAYMENT_API_TOKEN')
        ];
    }

    /**
     * Prepare a transaction for payment.
     *
     * @param Request $request
     * @return Response
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function prepare(Request $request)
    {
        try {
            // Send the request to the service.
            $response = Http::withHeaders($this->headers)
                ->post("$this->uri/mobile-money/safaricom/c2b/prepare", $request->all())
                ->json();

            // Set the response.
            $payload = $response;
            $status = 201;
        } catch (Exception $e) {
            $payload = $e->getMessage();
            $status = 404;
        } finally {
            return response($payload, $status);
        }
    }

    /**
     * Check if a transaction was completed.
     *
     * @param Request $request
     * @return Response
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function check(Request $request)
    {
        try {
            // Send the request to the service.
            $response = Http::withHeaders($this->headers)
                ->post("$this->uri/mobile-money/safaricom/c2b/check", $request->all())
                ->json();

            // Set the response.
            $payload = $response;
            $status = 201;
        } catch (Exception $e) {
            $payload = $e->getMessage();
            $status = 404;
        } finally {
            return response($payload, $status);
        }
    }

    /**
     * Mark a transaction as retrieved.
     *
     * @param array $data
     * @return Response
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    protected function retrieve(array $data)
    {
        try {
            // Send the request to the service.
            $response = Http::withHeaders($this->headers)
                ->post("$this->uri/mobile-money/safaricom/c2b/retrieve", $data)
                ->json();

            // Set the response.
            $payload = $response;
            $status = 200;
        } catch (Exception $e) {
            $payload = $e->getMessage();
            $status = 404;
        } finally {
            return response($payload, $status);
        }
    }
}
