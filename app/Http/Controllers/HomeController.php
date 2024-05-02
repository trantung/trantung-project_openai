<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\TestOpenai;

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
        $data = TestOpenai::paginate(10);
        return view('dashboard', compact('data'));
    }
}
