<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Roles;

class TeacherController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('teacher.index'), // URL động
                'text' => 'Danh sách giáo viên', // Text động
            ]
        ];

        return view('teacher.index', compact('breadcrumbs'));
    }
}
