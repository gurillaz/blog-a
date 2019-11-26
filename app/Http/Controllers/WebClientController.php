<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class WebClientController extends Controller
{
    public function home()
    {
        $latest_featured = Article::select(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])

            ->where('meta_is-feature', 'true')
            ->where('meta_status', 'published')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')
            ->orderBy('publishing_date', 'desc')->take(1)
            ->get();

        $others_featured = Article::select(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])

            ->where('id', '!=', $latest_featured[0]->id)
            ->where('meta_is-feature', 'true')
            ->where('meta_status', 'published')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')

            ->orderBy('publishing_date', 'desc')->take(3)
            ->get();


        $latest = Article::select(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])
            ->where('meta_status', 'published')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')
            ->orderBy('publishing_date', 'desc')->take(10)
            ->get();
        $categories = Category::select(['id', 'name'])->withCount('articles')->orderBy('articles_count', 'desc')->take(12)
            ->get();


        // $latest = Article::where('met')orderBy('publishing_date','desc')->take('10')->get();

        $resources = [];
        $resources['latest_featured'] = $latest_featured;
        $resources['others_featured'] = $others_featured;
        $resources['latest'] = $latest;
        $resources['categories'] = $categories;



        return Response::json([
            'resources' => $resources
        ], 200);
    }
    public function admin_home()
    {
        $latest_featured = Article::select(['id', 'slug', 'title', 'category_id', "publishing_date", 'created_at', 'meta_status', 'user_id'])
            ->where('publishing_date', '>', Carbon::now()->startOfMonth()->toDateTimeString())
            ->where('meta_is-feature', 'true')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')
            ->orderBy('publishing_date', 'desc')
            ->get();

        $pending_articles = Article::select(['id', 'slug', 'title', 'category_id', "publishing_date", 'created_at', 'meta_status', 'user_id'])

            ->where('meta_status', 'pending')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')

            ->orderBy('created_at', 'desc')
            ->get();


        $pending_comments = Comment::select(['id', 'commentable_id', 'commentable_type', 'body', 'created_at', 'meta_status', 'user_id'])
            ->where('meta_status', 'pending')
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')


            ->get()->map(function ($comment) {
                if ($comment->commentable_type == Comment::class) {
                    // $comment['article'] = $comment->commentable()->first('commentable_id')['commentable_id'];
                    $comment['article'] = Article::where('id', $comment->commentable()->first('commentable_id')['commentable_id'])->first(['id', 'slug', 'title']);
                } else {
                    $comment['article'] = $comment->commentable()->first(['id', 'slug', 'title']);
                }
                return $comment;
            });



        // $latest = Article::where('met')orderBy('publishing_date','desc')->take('10')->get();

        $resources = [];
        $resources['latest_featured'] = $latest_featured;
        $resources['pending_articles'] = $pending_articles;
        $resources['pending_comments'] = $pending_comments;



        return Response::json([
            'resources' => $resources
        ], 200);
    }
    public function pending_articles()
    {


        $pending_articles = Article::select(['id', 'slug', 'title', 'category_id', "publishing_date", 'created_at', 'meta_status', 'user_id'])

            ->where('meta_status', 'pending')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')

            ->orderBy('created_at', 'desc')
            ->get();

        $denied_articles = Article::select(['id', 'slug', 'title', 'category_id', "publishing_date", 'created_at', 'meta_status', 'user_id'])

            ->where('meta_status', 'deleted')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')

            ->orderBy('created_at', 'desc')
            ->get();


        $resources = [];
        $resources['pending_articles'] = $pending_articles;
        $resources['denied_articles'] = $denied_articles;



        return Response::json([
            'resources' => $resources
        ], 200);
    }
    public function approve_article(Article $article)
    {

        $article->meta_status = "published";
        $article->publishing_date = Carbon::now()->toDateTimeString();



        return Response::json([
            'msg' => "success"
        ], 200);
    }
    public function deny_article(Article $article)
    {
        //TODO set this to denied
        $article->meta_status = "deleted";
        $article->publishing_date = NULL;



        return Response::json([
            'msg' => "success"
        ], 200);
    }
    public function aprove_all_articles()
    {
        $articles = Article::where('meta_status', 'pending')->get();
        //TODO set this to denied
        foreach ($articles as $article) {
            $article->meta_status = "published";
            $article->publishing_date = Carbon::now()->toDateTimeString();
        };




        return Response::json([
            'msg' => "success"
        ], 200);
    }
    public function pending_comments()
    {


        $pending_comments = Comment::select(['id', 'commentable_id', 'commentable_type', 'body', 'created_at', 'meta_status', 'user_id'])
            ->where('meta_status', 'pending')
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')


            ->get()->map(function ($comment) {
                if ($comment->commentable_type == Comment::class) {
                    // $comment['article'] = $comment->commentable()->first('commentable_id')['commentable_id'];
                    $comment['article'] = Article::where('id', $comment->commentable()->first('commentable_id')['commentable_id'])->first(['id', 'slug', 'title']);
                } else {
                    $comment['article'] = $comment->commentable()->first(['id', 'slug', 'title']);
                }
                return $comment;
            });
        $denied_comments = Comment::select(['id', 'commentable_id', 'commentable_type', 'body', 'created_at', 'meta_status', 'user_id'])
            ->where('meta_status', 'deleted')
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')


            ->get()->map(function ($comment) {
                if ($comment->commentable_type == Comment::class) {
                    // $comment['article'] = $comment->commentable()->first('commentable_id')['commentable_id'];
                    $comment['article'] = Article::where('id', $comment->commentable()->first('commentable_id')['commentable_id'])->first(['id', 'slug', 'title']);
                } else {
                    $comment['article'] = $comment->commentable()->first(['id', 'slug', 'title']);
                }
                return $comment;
            });




        $resources = [];
        $resources['pending_comments'] = $pending_comments;
        $resources['denied_comments'] = $denied_comments;



        return Response::json([
            'resources' => $resources
        ], 200);
    }
    public function approve_comment(Comment $comment)
    {

        $comment->meta_status = "published";



        return Response::json([
            'msg' => "success"
        ], 200);
    }
    public function deny_comment(Comment $comment)
    {
        //TODO set this to denied
        $comment->meta_status = "deleted";
        // $article->publishing_date = NULL;



        return Response::json([
            'msg' => "success"
        ], 200);
    }
    public function aprove_all_comments()
    {
        $comments = Comment::where('meta_status', 'pending')->get();
        //TODO set this to denied
        foreach ($comments as $comment) {
            $comment->meta_status = "published";
        };




        return Response::json([
            'msg' => "success"
        ], 200);
    }
}
