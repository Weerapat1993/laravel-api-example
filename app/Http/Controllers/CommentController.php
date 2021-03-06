<?php

/**
 * @api {get} /comments GET Comment List
 * @apiSampleRequest /api/comments
 * @apiParam {Number} article_id  Comment List by Article ID
 * @apiName commentList
 * @apiGroup Comment
 */

/**
 * @api {get} /comments/:id GET Comment By ID
 * @apiSampleRequest /api/comments/1
 * @apiName commentByID
 * @apiGroup Comment
 */

/**
 * @api {post} /comments POST Comment Create
 * @apiSampleRequest /api/comments
 * @apiParam {String} comment  Comment
 * @apiParam {Number} article_id  Article ID
 * @apiParam {Number} user_id  User ID
 * @apiName commentCreate
 * @apiGroup Comment
 */

/**
 * @api {delete} /comments DELETE Comment Delete
 * @apiSampleRequest /api/comments
 * @apiParam {Number} id  Comment ID
 * @apiName commentDelete
 * @apiGroup Comment
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->tableName = 'comments';
        $this->primaryKey = 'id';
        $this->isJoin = true;
    }

    public function Model()
    {
        $models = Comment::join('users','users.id','=','comments.user_id')
            ->select('comments.*','users.name as user_name','users.avatar')
            ->orderBy('comments.created_at', 'asc');
        return $models;
    }

    public function getCommentByArticleID($id) {
        if($id) {
            $comments = $this->Model()->where('article_id', $id)->get();
        } else {
            $comments = $this->Model()->get();
        }
        return $comments;
    }

    public function getListByArticleID(Request $request) {
        $id = $request->get('article_id');
        $comments = $this->getCommentByArticleID($id);
        return $this->getSuccess(200, $comments);
    }

    public function create(CommentRequest $request)
    {
        $model = new Comment;

        $model->comment = $request->comment;
        $model->article_id = $request->article_id;
        $model->user_id = $request->user_id;

        $model->save();
        $modelByID = $this->dataByID($model->id);
        return $this->getSuccess(201, $modelByID);
    }
}
