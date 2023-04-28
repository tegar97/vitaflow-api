<?php

namespace App\Http\Controllers;

use App\Helper\imageResizer;
use App\Models\Article;
use App\Models\Category;
use App\Models\category_article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function getArticleData()
    {
        $articles = Article::all();
        $result = array();

        foreach ($articles as $article) {
            $wordCount = str_word_count(strip_tags($article->content));
            $readingTime = ceil($wordCount / 200); // assuming 200 words per minute reading speed
            $result[] = [
                'id' => $article->id,
                'name' => $article->name,
                'image' => $article->image,
                'content' => $article->content,
                'category_id' => $article->category_article_id,
                'reading_time' => $readingTime // estimated reading time in minutes
            ];
        }

        // return with json
        return response()->json([
            'message' => 'Success',
            'data' => $result
        ], 200);

    }

    public function getArticleDetail($articleId)
    {
        $article = Article::find($articleId);

        $wordCount = str_word_count(strip_tags($article->content));
        $readingTime = ceil($wordCount / 200); // assuming 200 words per minute reading speed

        $result = [
            'id' => $article->id,
            'name' => $article->name,
            'image' => $article->image,
            'content' => $article->content,
            'category_id' => $article->category_article_id,
            'reading_time' => $readingTime // estimated reading time in minutes
        ];

        // return with json
        return response()->json([
            'message' => 'Success',
            'data' => $result
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $articles = Article::all();

        return view('pages.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = category_article::all();

        return view('pages.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required',
            'content' => 'required',
            'category_id' => 'required',
        ]);

        $image = $request->file('image');
        $getImageName = imageResizer::ResizeImage($image, 'images', 'images', 300, 300);


        $article = new Article();
        $article->name = $request->title;
        $article->image = $getImageName;
        $article->content = $request->content;


        $article->category_article_id = $request->category_id;
        $article->save();

        return redirect()->route('articles.index')
            ->with('success', 'Product created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
