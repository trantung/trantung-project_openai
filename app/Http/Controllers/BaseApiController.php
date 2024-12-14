<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Storage;

class BaseApiController extends Controller
{
	public function getDataFromRequest($request)
	{
		return $request->json()->all();
	}

	public function responseSuccess($statusCode, $message)
	{
		return response()->json(array(
            'code' => $statusCode,
            'data' => $message,
            'message' => 'Success'
        ), 200);
	}

	public function responseError($statusCode, $message)
	{
		return response()->json(array(
            'code' => $statusCode,
            'data' => array(
                'messages' => $message,
            ),
            'message' => 'Fail'
        ), 200);
	}

	public function getDefaultParam()
	{
		return [
			'username', 'model_name'
		];
	}

    public function checkValidateParam($data, $parameters = array())
    {

    	$params = $this->getDefaultParam();
    	if(!empty($parameters)) {
    		$params = $parameters;
    	}
    	foreach($params as $param)
    	{
    		if(empty($data[$param])) {
    			return false;
    		}
    	}
    	return true;
    }

    public function convertJsonToJsonl($datas)
    {
    	$newContent = '';
        foreach($datas as $data){
            // foreach($data['messages'] as $value) {
	        $newContent .= '{"messages":[{"role":"system","content":"'.$data['messages'][0]['content'].'"},{"role":"user","content":"'.$data['messages'][1]['content'].'"},{"role":"assistant","content":"'.$data['messages'][2]['content'].'"}]}'."\n";
            // }
        }
        return $newContent;
    }

    public function putFileJsonl($username, $fileName, $data)
    {
    	Storage::disk('local')->put('/'. $username . '/' . $fileName . '.jsonl', $data);
    	return $this->getPathFileJsonl($username, $fileName);
    }

    public function putFileHistory($username, $filename, $userModelDataId, $data, $objectId = null)
    {
    	Storage::disk('local')->put('/user_model_data_id_'. $userModelDataId . '/' . $filename . '.json', json_encode($data));
    	return $filename;
    }

    public function getPathFileHistory($username, $userModelDataId, $objectId = null)
    {
    	return storage_path('app/user_model_data_id_'. $userModelDataId . '_' . $objectId . '.json');
    }

    public function getPathFileJsonl($username, $fileName)
    {
    	return storage_path('app/'. $username . '/' . $fileName . '.jsonl');
    }
}
