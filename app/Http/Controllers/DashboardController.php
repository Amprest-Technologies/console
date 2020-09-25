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
        return Inertia::render('Dashboard', [
            'projects' => $request->user()->currentTeam->projects
        ]);
    }
}
