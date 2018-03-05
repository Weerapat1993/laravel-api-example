<?php

namespace App\Http\Controllers;

use Route;
use App\Article;
use Illuminate\Http\Request;

class ReactController extends Controller
{
    public function show(Request $request) {
        $path = array_first(Route::current()->parameters);
        if ($request->is('article/*')) { 
            $key = str_replace('article/', '', $path);
            $article = Article::where('id', $key)->firstOrFail();
            return view('react', [
                'path' => $path,
                'title' => $article->title,
                'description' => $article->description,
            ]);
        }
        if($path) {
            return view('react', ['path' => $path]);
        }
        return view('react', ['path' => '/']);
    }
}
