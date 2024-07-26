<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\LmsCompletionActivity;
use DB;

class LmsCompletionActivityController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'username' => 'required|string',
            'course_id' => 'required|string',
            'section_id' => 'required|string',
            'video_id' => 'required|string',
            'status' => 'required|integer',
        ]);
        // Find the existing record or create a new one
        $activity = LmsCompletionActivity::updateOrCreate(
            [
                'username' => $validated['username'],
                'course_id' => $validated['course_id'],
                'section_id' => $validated['section_id'],
                'video_id' => $validated['video_id'],
            ],
            [
                'status' => $validated['status'],
            ]
        );

        // Return response
        return response()->json(['success' => true, 'activity' => $activity], 200);
    }
    
    public function detail(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'username' => 'required|string',
            'course_id' => 'required|integer',
        ]);

        $username = $validated['username'];
        $course_id = $validated['course_id'];
        $section_id = $request->input('section_id'); // Optional parameter
        if ($section_id) {
            // Retrieve the activities for the given section
            $activities = LmsCompletionActivity::where('username', $username)
                ->where('course_id', $course_id)
                ->where('section_id', $section_id)
                ->get();

            $totalActivities = $activities->count();
            $completedActivities = $activities->where('status', 1)->count();

            $completionPercentage = $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100) : 0;

            return response()->json([
                'totalActivities' => $totalActivities,
                'completedActivities' => $completedActivities,
                'completionPercentage' => $completionPercentage,
            ], 200);
        } else {
            // Retrieve all activities for the given course
            $sections = LmsCompletionActivity::where('username', $username)
                ->where('course_id', $course_id)
                ->select('section_id')
                ->distinct()
                ->get();

            $totalActivities = 0;
            $totalCompletedActivities = 0;

            foreach ($sections as $section) {
                $sectionActivities = LmsCompletionActivity::where('username', $username)
                    ->where('course_id', $course_id)
                    ->where('section_id', $section->section_id)
                    ->get();

                $sectionTotal = $sectionActivities->count();
                $sectionCompleted = $sectionActivities->where('status', 1)->count();

                $totalActivities += $sectionTotal;
                $totalCompletedActivities += $sectionCompleted;
            }

            $completionPercentage = $totalActivities > 0 ? round(($totalCompletedActivities / $totalActivities) * 100) : 0;

            return response()->json([
                'totalActivities' => $totalActivities,
                'completedActivities' => $totalCompletedActivities,
                'completionPercentage' => $completionPercentage,
            ], 200);
        }
    }
}
