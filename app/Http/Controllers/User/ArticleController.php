<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->get();
        return view('pages.user.article.index', compact('articles'));
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return view('pages.user.article.show', compact('article'));
    }
}
