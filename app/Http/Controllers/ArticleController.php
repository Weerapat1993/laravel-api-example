<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use Illuminate\Http\Request;
use App\Article;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->tableName = 'articles';
        $this->primaryKey = 'id';
        $this->isJoin = true;
    }

    public function Model()
    {
        $articles = Article::join('users','users.id','=','articles.user_id')
            ->select('articles.*','users.name as user_name','users.avatar')
            ->orderBy('articles.created_at', 'desc');
        return $articles;
    }

    public function create(ArticleRequest $request)
    {
        $article = new Article;

        $article->title = $request->title;
        $article->description = $request->description;
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
        ]);
        $articleByID = $this->dataByID($id);
        return $this->getSuccess(200, $articleByID);
    }
}
