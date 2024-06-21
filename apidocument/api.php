<?php
/**
 * @api {post} openai/modeldata/create Create model data
 * @apiName writeTask2Create
 * @apiGroup ApiCms
 * 
 * @apiParam {String} token token token for cho CMS
 * @apiParam {String} username username is not empty
 * @apiParam {String} user_id id of user
 * @apiParam {String} topic is prompt of essay
 * @apiParam {String} question content of essay
 * @apiSuccess {Integer} status Status of response
 * @apiSuccess {String} data  data response
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
{
    "code": 200,
    "data": {
        "question_id": 2
    },
    "message": "Success"
}
*/
?>