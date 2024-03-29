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
    protected $uri;

    public function __construct()
    {
        // Get the base URI and headers
        $this->uri = env('AMPREST_PAYMENT_API_URI');
        $this->headers = [
            'Api-Token' => env('AMPREST_PAYMENT_API_TOKEN'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
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
            $url = ($mpesaCredentials->project->pay_validation_callback ?? false);

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
    public function retrieve(Request $request)
    {
        try {
            // Send the request to the service.
            $response = Http::withHeaders($this->headers)
                ->post("$this->uri/mobile-money/safaricom/c2b/retrieve", $request->all())
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
     * Trigger an STK push
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @author Alvin G. Kaburu <geekaburu@amprest.co.ke>
     */
    public function triggerSTK(Request $request)
    {
        //  Get the M-Pesa credentials.
        try {
            //  Get the mpesa credentials
            $mpesaCredentials = MPesaCredentials::where('short_code', $request->short_code)
                ->firstOrFail();

            // Build the payload
            $payload = [
                'business_short_code' => $mpesaCredentials->short_code,
                'bill_ref_number' => $request->bill_ref_number,
                'msisdn' => $request->phone_number,
                'transaction_amount' => $request->amount,
                'transaction_desc' => $request->transaction_desc,
                'business_short_code_type' => $mpesaCredentials->short_code_type
            ];

            //  Append missing headers
            $headers = array_merge($this->headers, [
                'Pass-Key' => $mpesaCredentials->pass_key,
                'Encoded-Keys' => base64_encode("{$mpesaCredentials->consumer_key}:{$mpesaCredentials->consumer_secret}")
            ]);

            // Send the request to the pay service.
            $response = Http::withHeaders($headers)
                ->post("$this->uri/mobile-money/safaricom/c2b/trigger-stk", $payload)
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

        try {
            //  Get the M-Pesa credentials.
            $mpesaCredentials = MPesaCredentials::query()
                ->where('short_code', $shortCode)
                ->first();

            //  Get the project
            $project = $mpesaCredentials->project ?? null;

            //  Get the call back
            $callback = $project->pay_transaction_callback ?? null;

            //  Throw an error if the credentials don't exist.
            if (!$mpesaCredentials) {
                throw new Exception("No credentials matching this short code were found", 404);
            }

            //  Throw an error if a project does not exist.
            if (!$project) {
                throw new Exception("No project is linked to these credentials", 404);
            }

            //  Throw an error if the URL is not found.
            if (!$callback) {
                throw new Exception("No callback URL was provided for this project", 404);
            }

            // Send the request to the service.
            $response = Http::post($callback, $request->all())->throw()->json();
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
                $request->all(), 
                $mpesaCredentials->project ?? null
            ));

            //  Return the response
            return response($payload, $status);
        }
    }

    /**
     * Check an account's balance
     *
     * @param Request $request
     * @return Response
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function balance(Request $request)
    {
        //  Get the shortCode
        $shortCode = $request->short_code ?? null;

        try {
            //  Get the M-Pesa credentials.
            $mpesaCredentials = MPesaCredentials::query()
                ->where('short_code', $shortCode)
                ->first();

            //  Get the project
            $project = $mpesaCredentials->project ?? null;

            //  Get the callback 
            $callback = $project->pay_balance_callback ?? null;

            //  Throw an error if the credentials don't exist.
            if (!$mpesaCredentials) {
                throw new Exception("No credentials matching this short code were found", 404);
            }

            //  Throw an error if a project does not exist.
            if (!$project) {
                throw new Exception("No project is linked to these credentials", 404);
            }

            //  Throw an error if the URL is not found.
            if (!$callback) {
                throw new Exception("No balance callback URL was provided for this project", 404);
            }

            //  Append missing headers
            $headers = array_merge($this->headers, [
                'Encoded-Keys' => base64_encode("{$mpesaCredentials->consumer_key}:{$mpesaCredentials->consumer_secret}")
            ]);

            // Send the request to the service.
            $response = Http::withHeaders($headers)
                ->post("$this->uri/mobile-money/safaricom/c2b/balance", [
                    'business_short_code' => $shortCode,
                    'identifier_type' => match($mpesaCredentials->short_code_type){
                        'till_no' => 'TillNumber',
                        default => 'PayBill'
                    },
                    'initiator' => $mpesaCredentials->app_user_name,
                    'initiator_password' => $mpesaCredentials->app_user_password,
                    'remarks' => $request->remarks,
                    'result_callback' => $mpesaCredentials->balance_callback
                ])
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
     * Pull a transaction from the pull api
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @author Alvin G. Kaburu <geekaburu@amprest.co.ke>
     */
    public function pull(Request $request): Response
    {
        //  Get the shortCode
        $shortCode = $request->short_code ?? null;

        //  Launch a try catch
        try {
            //  Get the M-Pesa credentials.
            $mpesaCredentials = MPesaCredentials::query()
                ->where('short_code', $shortCode)
                ->first();

            //  Get the project
            $project = $mpesaCredentials->project ?? null;

            //  Throw an error if the credentials don't exist.
            if (!$mpesaCredentials) {
                throw new Exception("No credentials matching this short code were found", 404);
            }

            //  Throw an error if a project does not exist.
            if (!$project) {
                throw new Exception("No project is linked to these credentials", 404);
            }

            //  Append missing headers
            $headers = array_merge($this->headers, [
                'Encoded-Keys' => base64_encode(
                    "{$mpesaCredentials->consumer_key}:{$mpesaCredentials->consumer_secret}"
                )
            ]);

            // Send the request to the service.
            $response = Http::withHeaders($headers)
                ->post("$this->uri/mobile-money/safaricom/c2b/pull", [
                    'business_short_code' => $shortCode,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'offset' => $request->offset,
                ])
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
}