<?php

namespace App\Http\Controllers\Pay\C2B;

use App\Events\MobileMoney\Mpesa\TransactionCompleted;
use App\Http\Controllers\Controller;
use App\Models\MPesaCredentials;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * Validate a transaction.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @author Alvin G. Kaburu <geekaburu@amprest.co.ke>
     */
    protected function valid(Request $request)
    {
        //  Get the shortCode
        $shortCode = $request->BusinessShortCode;

        //  Get the M-Pesa credentials.
        $mpesaCredentials = MPesaCredentials::where('short_code', $shortCode)->first();

        try{
            //  Get the url
            $url = ($mpesaCredentials->project->pay_validation_hook ?? false);

            //  Return a default true if no hook is defined
            if (!$url) {
                $response = [
                    'code' => 0, 'description' => 'Validation was successful'
                ];
            } else {
                // Send the request to the service.
                $response = Http::post($url, $request->all())->json();

                //  Determine if request is valid
                $valid = $response['code'] ?? 1;

                //  Define the payload
                $response = [
                    'code' => (int) $valid,
                    'description' => (int) $valid === 0 ? 'Validation was successful' : 'Validation has failed'
                ];
            }

            //  Determine the response
            $payload = $response;
            $status = 200;
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

    /**
     * Broadcast a confirmed transaction.
     *
     * @param Request $request
     * @return Response
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    protected function broadcast(Request $request)
    {
        //  Get the shortCode
        $shortCode = $request->BusinessShortCode;

        //  Get the M-Pesa credentials.
        $mpesaCredentials = MPesaCredentials::where('short_code', $shortCode)->first();
        
        try {
            //  Get the M-Pesa credentials.
            $mpesaCredentials = MPesaCredentials::where('short_code', $shortCode)
                ->first();

            //  Throw an error if the credentials don't exist.
            if (!$mpesaCredentials) {
                throw new Exception("No credentials matching this short code were found", 404);
            }

            //  Throw an error if a project does not exist.
            if (!$mpesaCredentials->project) {
                throw new Exception("No project is linked to these credentials", 404);
            }

            //  Throw an error if the URL is not found.
            if (!$mpesaCredentials->project->pay_callback) {
                throw new Exception("No callback URL was provided for this project", 404);
            }

            // Send the request to the service.
            $response = Http::post(
                $mpesaCredentials->project->pay_callback, $request->all()
            )->throw()->json();

            $payload = $response;
            $status = 200;
        } catch (Exception $e) {
            $payload = $e->getMessage();
            $status = in_array($e->getCode(), range(400, 600))
                ? $e->getCode()
                : 404;
        } finally {
            //  Throw an mpesa completed event
            event(new TransactionCompleted(
                $request->all(), $mpesaCredentials->project ?? null
            ));

            //  Return the response
            return response($payload, $status);
        }
    }
}
