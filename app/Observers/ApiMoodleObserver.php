<?php

namespace App\Observers;

use App\Models\ApiMoodle;
use App\Models\Course;
use App\Models\ApiEms;
use App\Models\ApiMoodleEms;

class ApiMoodleObserver
{
    /**
     * Handle the ApiMoodle "created" event.
     */
    public function created(ApiMoodle $apiMoodle): void
    {
        //
    }

    public function saved(ApiMoodle $apiMoodle)
    {
        // Thực hiện tác vụ sau khi lưu
        if($apiMoodle->moodle_type =='course') {
            $courseId = Course::create([
                'api_moodle_id' => $apiMoodle->id,
                'name' => $apiMoodle->moodle_name,
                'code' => $apiMoodle->code,
            ])->id;
            \Log::info('Observer: ApiMoodle saved with ID: ' . $apiMoodle->id . ' and course_id: ' . $courseId);
        }
        \Log::info('api moodle ' . $apiMoodle->id . ' has type: ' . $apiMoodle->moodle_type . ' saved');
    }
    /**
     * Handle the ApiMoodle "updated" event.
     */
    public function updated(ApiMoodle $apiMoodle): void
    {
        if($apiMoodle->moodle_type =='course') {
            $check = Course::where('api_moodle_id', $apiMoodle->id)->update(['name' => $apiMoodle->moodle_name]);
            \Log::info('Observer: ApiMoodle updated with ID: ' . $apiMoodle->id);
        }
        // if($apiMoodle->moodle_type =='quiz') {
        //     ApiMoodleEms::where('api_moodle_id', $apiMoodle->id)->first();
        //     $check = ApiEms::where('api_moodle_id', $apiMoodle->id)->update(['name' => $apiMoodle->moodle_name]);
        //     \Log::info('Observer: ApiMoodle updated with ID: ' . $apiMoodle->id);
        // }
        \Log::info('api moodle ' . $apiMoodle->id . ' has type: ' . $apiMoodle->moodle_type . ' updated');
    }

    /**
     * Handle the ApiMoodle "deleted" event.
     */
    public function deleted(ApiMoodle $apiMoodle): void
    {
        Course::where('api_moodle_id', $apiMoodle->id)->delete();
    }

    /**
     * Handle the ApiMoodle "restored" event.
     */
    public function restored(ApiMoodle $apiMoodle): void
    {
        //
    }

    /**
     * Handle the ApiMoodle "force deleted" event.
     */
    public function forceDeleted(ApiMoodle $apiMoodle): void
    {
        //
    }
}
