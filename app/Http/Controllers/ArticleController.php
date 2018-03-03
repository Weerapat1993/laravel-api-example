<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Article;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    public function Article()
    {
        $articles = Article::join('users','users.id','=','articles.user_id')
                            ->select('articles.*','users.name as user_name','users.avatar');
        return $articles;
    }

    public function ArticleByID($id)
    {
        $article = $this->Article()
                ->where('articles.id', '=', $id)
                ->firstOrFail();
        return $article;
    }

    public function getList() {
        $articles = $this->Article()->get();
        return response()->json([
            'data' => $articles,
            'code' => 200,
            'status' => 'OK',
        ]);
    }

    public function getByID($id, Request $request)
    {
        try {
            $article = $this->ArticleByID($id);
            return response()->json([
                'data' => $article,
                'code' => 200,
                'status' => 'OK',
            ]);
        } catch(Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
                'code' => 500,
                'status' => 'Internal Server Error',
            ], 500);
        }
        
    }

    public function create(ArticleRequest $request)
    {
        $article = new Article;

        $article->title = $request->title;
        $article->description = $request->description;
        $article->user_id = $request->user_id;

        $article->save();
        $articleByID = $this->ArticleByID($article->id);
        return response()->json([
            'data' => $article2,
            'code' => 201,
            'status' => 'Created',
        ], 201); 
    }

    public function update(ArticleRequest $request)
    {
        $id = $request->id;
        Article::where('id', $id)->update([
            'title' => $request->title, 
            'description' => $request->description,
        ]);
        $articleByID = $this->ArticleByID($id);
        return response()->json([
            'data' => $articleByID,
            'code' => 200,
            'status' => 'Updated',
        ], 201); 
    }

    public function delete(Request $request)
    {
        # code...
    }
}
