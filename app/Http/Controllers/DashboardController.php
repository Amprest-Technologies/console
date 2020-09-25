<?php

namespace App\Http\Controllers;

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
        $projects = $request->user()->currentTeam->projects;

        // Return the view and the data.
        return Inertia::render('Dashboard', [
            'projects' => $projects
        ]);
    }
}
