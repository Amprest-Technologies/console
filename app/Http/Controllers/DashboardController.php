<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function dashboard(Request $request)
    {
        // Get the user's current team's projects.
        $projects = $request->user()
            ->currentTeam
            ->projects;

        // Return the view and the data.
        return Inertia::render('Dashboard', [
            'projects' => $projects
        ]);
    }

    /**
     * Show the project details
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function projectDetails(Project $project)
    {
        return Inertia::render('Projects/Show', [
            'project' => $project
        ]);
    }

    /**
     * Subscribe a project to a particular service.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function subscribeToService(Project $project, Service $service)
    {
        return Inertia::render('Projects/Subscribe', [
            'project' => $project,
            'service' => $service->load([
                'tiers' => fn ($query) => $query->public()
            ])
        ]);
    }

    public function storeSubscription(Request $request, Project $project)
    {
        try {
            // Attach the subscription to the project.
            $project->subscriptions()->create(
                $request->merge([
                    'expires_at' => now()->addMonth()
                ])->all()
            );

            // Set the payload.
            $payload = "Successfully created a new subscription.";
            $status = 201;
        } catch (Exception $e) {
            $payload = $e->getMessage();
            $status = 404;
        } finally {
            return response($payload, $status);
        }
    }
}
