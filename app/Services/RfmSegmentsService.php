<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class RfmSegmentsService
{
    protected string $url;

    protected Client $client;

    public function __construct()
    {
        $this->url = config('vivarolls.api');
        $this->client = new Client();
    }


    /**
     * @return string|null
     */
    public function token(): ?string
    {
        try {
            $request = $this->client->request('POST', "{$this->url}/user/token", [
                'form_params' => [
                    'username' => config('vivarolls.username'),
                    'password' => config('vivarolls.password')
                ]
            ]);

            $response = json_decode($request->getBody(), true);

            return $response['access_token'] ?? null;
        } catch (GuzzleException $exception) {
            Log::info($exception->getMessage());
            return null;
        }
    }

    /**
     * @param string $token
     * @return array
     */
    public function reports(string $token): array
    {
        try {
            $request = $this->client->request('GET', "{$this->url}/reports", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

            return json_decode($request->getBody(), true);
        } catch (GuzzleException $exception) {
            Log::info($exception->getMessage());
            return [];
        }
    }

    /**
     * @param array $reports
     * @return string|null
     */
    public function getSegmentRfmId(array $reports): ?string
    {
        $reportId = null;
        foreach ($reports['grouped']['General'] as $report) {
            if ($report['name'] === 'get_segment_rfm') {
                $reportId = $report['id'];
                break;
            }
        }

        return $reportId;
    }

    /**
     * @return JsonResponse
     */
    public function segments(): JsonResponse
    {
        try {
            if ($token = $this->token()) {
                $reports = $this->reports($token);
                if (!empty($reports)) {
                    if ($segmentRfmId = $this->getSegmentRfmId($reports)) {
                        $request = $this->client->request('POST', "{$this->url}/report/$segmentRfmId/run", [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $token,
                                'Content-Type' => 'application/json',
                                'accept' => 'application/json'
                            ],
                            'json' => []
                        ]);

                        $response = json_decode($request->getBody(), true);
                        $segments = $response['aggregations']['segments']['buckets'] ?? [];
                        $total = $response['total'];
                        if (count($segments)) {
                            $segments = collect($segments)->map(function ($segment) use ($total) {
                                $clientsCount = $segment['doc_count'];
                                $ordersCount = $segment['orders']['value'];
                                $sumOrders = $segment['total']['value'];
                                $avgBill = round($sumOrders/$ordersCount);

                                $segmentTotal = ($clientsCount * 100)/$total;
                                return [
                                    'title' => $segment['key'],
                                    'count_clients' => Number::format($clientsCount),
                                    'count_orders' => Number::format($ordersCount),
                                    'sum_orders' => Number::format($sumOrders) . ' ₽',
                                    'avg_bill' => Number::format($avgBill) . ' ₽',
                                    'total' => round($segmentTotal) . ' %'
                                ];
                            });
                        }

                        return Response::json([
                            'success' => true,
                            'segments' => $segments,
                            'total' => $response['total']
                        ]);
                    }
                    return Response::json([
                        'success' => false,
                        'message' => 'RFM segment not found'
                    ], 404);
                }
                return Response::json([
                    'success' => false,
                    'message' => 'Reports not found'
                ], 404);
            }
            return Response::json([
                'success' => false,
                'message' => 'Something went wrong !'
            ], 422);
        } catch (GuzzleException $exception) {
            Log::info($exception->getMessage());
            return Response::json([
                'success' => false,
                'message' => 'Something went wrong !'
            ], 400);
        }
    }


}
