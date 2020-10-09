<?php

namespace App\Http\Controllers\Pay\C2B;

use App\Http\Controllers\Controller;
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
        $this->headers = ['Api-Token' => env('AMPREST_PAYMENT_API_TOKEN')];
    }

    public function prepare(Request $request)
    {
        return $request->all();
        try {
            // Send the request to the service.
            $response = Http::withHeaders($this->headers)
                ->post("$this->uri/mobile-money/safaricom/c2b/prepare", $request->all());

            // Set the response.
            $payload = $response;
            $status = 201;
        } catch (\Throwable $th) {
            $payload = $th->getMessage();
            $status = 404;
        } finally {
            return response($payload, $status);
        }
    }

    public function check(Request $request)
    {
        try {
            // Send the request to the service.
            $response = Http::withHeaders($this->headers)
                ->post("$this->uri/mobile-money/safaricom/c2b/check", $request->all());

            // Set the response.
            $payload = $response;
            $status = 201;
        } catch (\Throwable $th) {
            $payload = $th->getMessage();
            $status = 404;
        } finally {
            return response($payload, $status);
        }
    }
}
