<?php

namespace App\Http\Controllers\Message\BulkSMS;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SMSController extends Controller
{
    protected $baseURI;
    protected $headers;

    public function __construct()
    {
        // Get the base URI and headers
        $this->uri = env('AMPREST_MESSAGING_API_URI');
        $this->headers = [
            'Api-Token' => env('AMPREST_MESSAGING_API_TOKEN')
        ];
    }

    /**
     * Receive an incoming request and send it to
     * the Amprest Messaging Microservice to analyse a message.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @author Alvin G. Kaburu <geekaburu@amprest.co.ke>
     */
    public function analyse(Request $request, Project $project): Response
    {
        try {
            // Authorise the request.
            // $this->authorise('create', '')

            // Validate the data.
            $validator = Validator::make($request->all(), [
                'title' => ['sometimes', 'required', 'string'],
                'content' => ['required', 'string'],
                'recipients' => ['required', 'array'],
                'scheduled_for' => [
                    'sometimes', 'required', 'date', 'after_or_equal:today'
                ]
            ]);

            // Throw an error if the validation fails.
            if ($validator->fails()) {
                throw new Exception($validator->errors(), 422);
            }

            // Get the sender ID.
            if ($project->senderId === null) {
                throw new Exception("No Sender ID has been registered.", 401);
            }

            // Get the user details necessary for the request.
            $data = $request->merge([
                'sender_id' => $project->senderId->code,
                'cost_per_message' => 1.00
            ])->all();

            // Submit the request to the microservice.
            $response = Http::withHeaders($this->headers)
                ->post("$this->uri/bulk-sms/analyse", $data)
                ->json();

            // Process that a request was made.
            //

            // Return the results.
            $payload = $response;
            $status = 201;
        } catch (Exception $e) {
            $payload = $e->getMessage();
            $status = $e->getCode() ?? 404;
        } finally {
            return response($payload, $status);
        }
    }

    /**
     * Receive an incoming request and send it to
     * the Amprest Messaging Microservice to send a message.
     *
     * @param Request $request
     * @param int $uuid Project Uuid.
     * @param string $messageId Message Uuid.
     * @return Response
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @author Alvin G. Kaburu <geekaburu@amprest.co.ke>
     */
    public function send(Request $request, int $uuid, string $messageId): Response
    {
        try {
            // Authorise the request.
            // $this->authorise('create', '')

            // Submit the request to the microservice.
            $response = Http::withHeaders($this->headers)
                ->post("$this->uri/bulk-sms/send/$messageId")
                ->json();

            // Process that a request was made.
            //

            // Return the results.
            $payload = $response;
            $status = 201;
        } catch (Exception $e) {
            $payload = $e->getMessage();
            $status = $e->getCode() ?? 404;
        } finally {
            return response($payload, $status);
        }
    }
}
