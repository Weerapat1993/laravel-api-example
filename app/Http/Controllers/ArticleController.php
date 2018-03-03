<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    public function getList() {
        $articles = Article::all();
        return response()->json([
            'data' => $articles,
            'code' => 200,
            'status' => 'OK',
        ]);
    }

    public function getByID($id, Request $request)
    {
        try {
            $article = Article::where('id', '=', $id)->firstOrFail();
            return response()->json([
                'data' => $article,
                'code' => 200,
                'status' => 'OK',
            ]);
        } catch(Exception $e) {
            return response()->json([
                'data' => 'Data is not Found.',
                'code' => 500,
                'status' => 'Internal Server Error',
            ], 500);
        }
        
    }

    public function create(Request $request)
    {
        # code...
    }

    public function update(Request $request)
    {
        # code...
    }

    public function delete(Request $request)
    {
        # code...
    }
}
