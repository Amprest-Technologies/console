<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;

class VerifySubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure     $next
     * @param  string       $service
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $service)
    {
        // Get the API key.
        $apiKey = $request->header('x-api-key');

        // If no API key was provided, return a failed response.
        if (!$apiKey) {
            return response("Failed! No API Key provided.", 403);
        }

        // Get the project.
        $project = Project::withActiveSubscriptions()
            ->where('api_key', $apiKey)
            ->first();

        // If the API key does not match an existing project,
        // return a failed response.
        if (!$project) {
            return response("Failed! Incorrect API Key.", 403);
        }

        // Proceed to the Console.
        return $next($request);
    }
}
