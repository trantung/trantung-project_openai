<?php

/**
 * @api {post} domain_lms/api/fe/student/get-info-user-by-token LMS get student information
 * @apiName ApiGetInfoStudentOfLms
 * @apiGroup ApiLmsStudent
 *
 * @apiParam {String} api_lms_key key is provided by lms serve
 * @apiParam {String} access_token access token of icanid
 * 
 * @apiSuccess {Integer} status Status of response
 * @apiSuccess {String} data  data response
 * @apiSuccess {String} message  message is success
 *
 * @apiSuccessExample Success-Response:
*{
*   "code": 200,
*   "data": {
*       "id": 7,
*       "user_id": null,
*       "name": "Trần Bình",
*       "username": "binht",
*       "email": "binht@hocmai.vn",
*       "created_at": "2024-12-31T07:40:08.000000Z",
*       "updated_at": "2024-12-31T07:40:08.000000Z",
*       "deleted_at": null,
*       "sso_name": "icanid",
*       "sso_id": "cc6a6ee3-244a-45cc-843b-99f804f40ba9",
*       "moodle_id": null
*   },
*   "message": "Success"
*}
*/

/**
 * @api {get} domain_ems/api/v1/auth/sso/lmsnew/validate_token EMS get access token
 * @apiName ApiGetAccessTokenFromEms
 * @apiGroup ApiEmsStudent
 *
 * @apiParam {String} apikey apikey of EMS provide
 * @apiParam {String} access_token access token of icanid
 * 
 * @apiSuccess {Integer} status Status of response
 * @apiSuccess {String} x-api-key EMS access_token
 * @apiSuccess {String} x-api-key-refresh  EMS refresh token response
 *
 * @apiSuccessExample Success-Response:
*   {
*   "status": true,
*   "x-api-key": "xccxccxxcxcxzcx",
*   "x-api-key-refresh": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9s"
*   }
*/

/**
 * @api {get} domain_ems/api/v1/auth/sso/lmsnew/refresh_token EMS get new refresh token of user
 * @apiName ApiGetRefreshTokenFromEms
 * @apiGroup ApiEmsStudent
 *
 * @apiParam {String} apikey apikey of EMS provide
 * @apiParam {String} x-api-key-refresh refresh token of user
 * 
 * @apiSuccess {Integer} status Status of response
 * @apiSuccess {String} x-api-key EMS access_token
 * @apiSuccess {String} x-api-key-refresh  EMS new refresh token response
 *
 * @apiSuccessExample Success-Response:
*   {
*   "status": true,
*   "x-api-key": "xccxccxxcxcxzcx",
*   "x-api-key-refresh": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9s"
*   }
*/

/**
 * @api {get} domain_ems/api/v1/auth/sso/lmsnew/refresh_token EMS get user info
 * @apiName ApiGetUserInfo
 * @apiGroup ApiEmsStudent
 *
 * @apiHeader {String} x-api-key access token of user
 * @apiParam {String} apikey apikey of EMS provide
 *
 * @apiSuccessExample Success-Response:
*   {
*   "status": true,
*   "data": {
*       "idStudent": "6333428f-96ad-4d5d-a69a-7cb5ef8d19c6",
*       "idOriginal": "7",
*       "username": "binht",
*       "firstName": "Trần Bình",
*       "lastName": null,
*       "phone": null,
*       "email": "binht@hocmai.vn",
*       "avatar": "https://apiems.hocmai.vn/resource/uploads/image/default_ava/Default_ava1.png",
*       "birthday": null,
*       "gender": null,
*       "address": null,
*       "district": null,
*       "city": null,
*       "school": null,
*       "appToken": null
*       }
*   }
*/

/**
 * @api {post} domain_lms/api/student/create-with-course Api create student with courses id
 * @apiName ApiCreateStudentWithCourse
 * @apiGroup ApiSale
 *
 * @apiParam {String} api_sale_key key of LMS provide
 * @apiParam {String} email email of student
 * @apiParam {String} course_ids course ids list seperate by comma. example: 1,2,3
 * 
 * @apiSuccessExample Success-Response:
* {
* "code": 200,
* "data": {
    * "success": [
        * "4",
        * "9",
        * "13"
    * ],
    * "fail": []
    * },
    * "message": "Success"
* }
*/

/**
 * @api {get} domain_lms/api/student/create-with-course Api list course
 * @apiName ApiCourseList
 * @apiGroup ApiSale
 * 
 * @apiSuccessExample Success-Response:
*{
*   "code": 200,
*   "data": {
*       "4": "Khóa học 1",
*       "9": "Khóa học 2",
*       "13": "Khóa học 3",
*       "35": "Khóa học 4"
*   },
*   "message": "Success"
*}
*/