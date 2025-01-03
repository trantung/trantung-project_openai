<?php

namespace App\Commons\Constants;


class CategoryValue
{
    const MOODLE_TYPE_COURSE = 1;

    const MOODLE_TYPE_ACTIVITY = 2;

    const MOODLE_TYPE = [
        self::MOODLE_TYPE_COURSE => 'course',
        self::MOODLE_TYPE_ACTIVITY => 'activity',
    ];
}
