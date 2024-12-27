<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDataFromRequest($request)
    {
        return $request->json()->all();
    }

    public function checkAccess($request, $arrayField)
    {
        $jsonData = $this->getDataFromRequest($request);
        foreach($arrayField as $value)
        {
            if(!isset($jsonData[$value]) || empty($jsonData[$value])) {
                return false;
            }
        }

        return $jsonData;
    }

    public function responseSuccess($statusCode, $data)
    {
        return response()->json(array(
            'code' => $statusCode,
            'data' => $data,
            'message' => 'Success'
        ), 200);
    }

    public function responseError($message = null)
    {
        if(!isset($message)) {
            $message = 'error';
        }
        return response()->json(array(
            'code' => 400,
            'data' => $message,
            'message' => 'Error'
        ), 200);
    }
}