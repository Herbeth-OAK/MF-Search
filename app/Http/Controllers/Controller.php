<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function emptySend(): Response
    {
        return response()->noContent(Response::HTTP_NO_CONTENT);
    }

    public function send($data = null, $status = 200, $headers = []): Response|JsonResponse
    {
        if ($data === null && $status === 204) {
            return $this->emptySend();
        }

        $responseData = $data ?? [];

        return response()->json([
            'status' => $status,
            'data' => $responseData,
        ], $status, $headers, empty($responseData) ? JSON_FORCE_OBJECT : JSON_ERROR_NONE);
    }

    public function sendError($data, $status): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'data' => [
                'messages' => $data,
            ],
        ], $status);
    }

    public function sendPaginatedData(LengthAwarePaginator $paginator): JsonResponse
    {
        return response()->json(
            [
                'status' => Response::HTTP_OK,
                'meta' => [
                    'total' => $paginator->total(),
                    'lastPage' => $paginator->lastPage(),
                    'perPage' => $paginator->perPage(),
                    'currentPage' => $paginator->currentPage(),
                ],
                'data' => $paginator->getCollection()->toArray(),
            ]
        );
    }

    public function noContent()
    {

    }
}
