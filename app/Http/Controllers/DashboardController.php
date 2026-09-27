<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $service,
    ) {
    }

    public function index(Request $request): Response
    {
        $statistics = $this->service->getStatistics($request->user());

        return Inertia::render('Dashboard', [
            'statistics' => $statistics->toArray(),
        ]);
    }
}
