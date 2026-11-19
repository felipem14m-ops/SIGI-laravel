<?php

namespace App\Http\Controllers\Api;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends ApiController
{
    public function __invoke(DashboardService $dashboard): JsonResponse
    {
        return $this->exito($dashboard->resumenApi());
    }
}
