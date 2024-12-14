<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\TestOpenai;
use App\Jobs\OpenAiJob;
use App\Models\User;
use App\Models\CustomerId;
use App\Models\Job;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
    
    }
    
    public function index()
    {
        return view('admin.dashboard');
    }

    public function index1()
    {
        $data = TestOpenai::orderBy('id', 'DESC')->paginate(10);
        return view('dashboard', compact('data'));
    }

    public function testQueue(Request $request)
    {
        $id = $request->input('id');
        DB::beginTransaction();
        try {
            $checkData = CustomerId::where('id', $id)->first();
            
            if (empty($checkData)) {
                // Nếu id chưa có trong bảng, insert dữ liệu mới và chạy queue
                $data = [
                    'question' => 'question',
                    'topic' => '1',
                    'user_id' => Auth::id(),
                    'status' => 0
                ];
                $questionTable = CustomerId::create($data);
                
                // Chạy queue
                dispatch(new OpenAiJob($questionTable->id, Auth::id()));
                
                DB::commit();
                return response()->json(['message' => 'Data inserted and job dispatched']);
            } else {
                if (empty($checkData->answer)) {
                    // Nếu id có trong bảng nhưng không có answer, chạy queue
                    dispatch(new OpenAiJob($id, Auth::id()));
                    
                    DB::commit();
                    return response()->json(['message' => 'Job dispatched to update answer']);
                } else {
                    DB::commit();
                    // Nếu có id và có answer, trả về answer
                    return response()->json(['answer' => $checkData->answer]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function testStreaming()
    {
        return view('testStreaming');
    }
}
