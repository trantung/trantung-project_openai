<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{

    /**
     * response
     * 
     * @param int $status
     * @param bool $success
     * @param string $message
     * @param array|string $data
     * @return JsonResponse
     */
    public function response(
        array|string $data = [],
        int $status = Response::HTTP_OK,
        bool $success = true,
        string $message = "Successfully"
        ): JsonResponse
    {
        $response = [
            'success' => $success,
            'message'=> $message,
        ];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, $status);
    }
}
