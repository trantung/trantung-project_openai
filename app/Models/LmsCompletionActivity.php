<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LmsCompletionActivity extends Model
{
    use HasFactory;
    
    protected $table = "lms_completion_activities";
    public $fillable = ['username', 'user_id', 'course_id', 'section_id', 'video_id', 'status'];
    public $timestamps = true;
    
    public static function getDefaultCourse()
    {
        //bai giang: section
        //khoa hoc: course
        //video: video
        //exercise: exercise
        //quiz: quiz_test
        $array = [
            'course_id' => 1,
            'course_name' => 'course_name',
            'list_section' => [
                [
                    'section_id' => 1,
                    'section_name' => 'section_name',
                    'list_video' => [
                        [
                            'video_id'=> 1,
                            'video_name'=> 'video_name',
                            'video_url' => 'video_url',
                            'list_exercise' => [
                                [
                                    'exercise_id' => 1,
                                    'exercise_name' => 'exercise_name_1',
                                ],
                                [
                                    'exercise_id' => 2,
                                    'exercise_name' => 'exercise_name_2',
                                ],
                            ],
                        ]
                    ],
                    'quiz_list' => [
                        [
                            'quiz_id' => 1,
                        ],
                        [
                            'quiz_id' => 2,
                        ],
                    ]
                ],
                [
                    'section_id' => 2,
                    'section_name' => 'section_name',
                    'list_video' => [
                        [
                            'video_id'=> 1,
                            'video_name'=> 'video_name',
                            'video_url' => 'video_url',
                            'list_exercise' => [
                                [
                                    'exercise_id' => 1,
                                    'exercise_name' => 'exercise_name_1',
                                ],
                                [
                                    'exercise_id' => 2,
                                    'exercise_name' => 'exercise_name_2',
                                ],
                            ],
                        ]
                    ],
                    'quiz_list' => [
                        [
                            'quiz_id' => 1,
                        ],
                        [
                            'quiz_id' => 2,
                        ],
                    ]
                ]
            ]
        ];
        return $array;
    }
}
