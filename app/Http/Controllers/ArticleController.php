<?php

/**
 * @api {get} /articles GET Article List
 * @apiSampleRequest /api/articles
 * @apiName articleList
 * @apiGroup Article
 */

/**
 * @api {get} /articles/:id GET Article By ID
 * @apiSampleRequest /api/articles/1
 * @apiName articleByID
 * @apiGroup Article
 */

/**
 * @api {post} /articles POST Article Create
 * @apiSampleRequest /api/articles
 * @apiParam {String} title  Title Stroy
 * @apiParam {String} description  Description
 * @apiParam {String} user_id     User ID
 * @apiName articleCreate
 * @apiGroup Article
 */


/**
 * @api {put} /articles PUT Article Update
 * @apiSampleRequest /api/articles
 * @apiParam {Number} id  Article ID
 * @apiParam {String} [title]  Title Stroy
 * @apiParam {String} [description]  Description
 * @apiParam {String} [user_id]     User ID
 * @apiName articleUpdate
 * @apiGroup Article
 */

/**
 * @api {delete} /articles DELETE Article Delete
 * @apiSampleRequest /api/articles
 * @apiParam {Number} id  Article ID
 * @apiName articleDelete
 * @apiGroup Article
 */

namespace App\Http\Controllers;

use Exception;
use Validator;
use Illuminate\Http\Request;
use App\Article;
use App\Http\Controllers\CommentController;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    public function __construct(CommentController $commentController)
    {
        $this->tableName = 'articles';
        $this->primaryKey = 'id';
        $this->isJoin = true;
        $this->comment = $commentController;
    }

    public function Model()
    {
        $articles = Article::join('users','users.id','=','articles.user_id')
            ->select('articles.*','users.name as user_name','users.avatar')
            ->orderBy('articles.created_at', 'desc');
        return $articles;
    }

    /**
     * Response Data Model By ID
     * @return Array
     */
    public function getByID($id, Request $request)
    {
        try {
            $article = $this->dataByID($id);
            $article->comment = $this->comment->getCommentByArticleID($id);
            return $this->getSuccess(200, $article);
        } catch(Exception $e) {
            return $this->getFailure(404, $e->getMessage());
        }
        
    }

    public function create(ArticleRequest $request)
    {
        $article = new Article;

        $article->title = $request->title;
        $article->description = $request->description;
        $article->image_url = $request->image_url;
        $article->user_id = $request->user_id;

        $article->save();
        $articleByID = $this->dataByID($article->id);
        return $this->getSuccess(201, $articleByID);
    }

    public function update(ArticleRequest $request)
    {
        $id = $request->id;
        Article::where($this->primaryKey, $id)->update([
            'title' => $request->title, 
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);
        $articleByID = $this->dataByID($id);
        return $this->getSuccess(200, $articleByID);
    }
}
