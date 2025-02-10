<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%')
                  ->orWhere('content', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('name', $request->input('category'));
            });
        }

        if ($request->has('author')) {
            $query->where('author', $request->input('author'));
        }

        $articles = $query->get();

        return response()->json($articles);
    }
}