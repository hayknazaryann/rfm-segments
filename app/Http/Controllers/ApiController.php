<?php

namespace App\Http\Controllers;

use App\Services\RfmSegmentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    /**
     * @param RfmSegmentsService $requestService
     */
    public function __construct(
        protected RfmSegmentsService $requestService
    )
    {}

    /**
     * @return JsonResponse
     */
    public function rfmSegments(): JsonResponse
    {
        return $this->requestService->segments();

    }
}
