<?php

namespace App\Http\Controllers;

use App\Helper\ImageResizer;
use App\Models\Article;
use App\Models\Category;
use App\Models\category_article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ArticleController extends Controller
{

    public function getArticleData()
    {
        $redis = Redis::connection(); // memanggil koneksi Redis

        // memeriksa apakah data sudah tersimpan di cache
        if ($redis->exists('article_data')) {
            $result = json_decode($redis->get('article_data'), true);
        } else {
            $articles = Article::all();
            $result = array();

            foreach ($articles as $article) {
                $wordCount = str_word_count(strip_tags($article->content));
                $readingTime = ceil($wordCount / 200);
                $category = category_article::find($article->category_article_id); // get the related category
                $categoryName = $category->name;
                // assuming 200 words per minute reading speed
                $result[] = [
                    'id' => $article->id,
                    'name' => $article->name,
                    'image' => $article->image,
                    'content' => $article->content,
                    'category_id' => $article->category_article_id,
                    'category' => $categoryName,
                    'reading_time' => $readingTime // estimated reading time in minutes
                ];
            }

            // menyimpan hasil ke dalam cache Redis
            $redis->set('article_data', json_encode($result));
            $redis->expire('article_data', 60); // set expire time 60 detik

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
        $getImageName = ImageResizer::ResizeImage($image, 'images', 'images', 300, 300);


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
