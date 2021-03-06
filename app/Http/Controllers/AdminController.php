<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Comment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{

    public function admin_home()
    {
        $latest_featured = Article::select(['id', 'slug', 'title', 'category_id', "publishing_date", 'created_at', 'meta_status', 'meta_list_place', 'user_id'])
            // ->where('publishing_date', '>', Carbon::now()->startOfMonth()->toDateTimeString())
            ->where('meta_is-feature', 'true')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')
            ->orderBy('meta_list_place', 'asc')
            ->get();

        $pending_articles = Article::select(['id', 'slug', 'title', 'category_id', "publishing_date", 'created_at', 'meta_status', 'meta_list_place', 'user_id'])

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


    public function featured_order()
    {
        $resources = [];

        $articles = Article::query()
            ->where('meta_status', 'published')
            ->with('user:id,name')
            ->with('category:id,name')
            ->orderBy('created_at', 'desc')->get();


        $currently_featured = Article::query()
            ->where('meta_status', 'published')

            ->where('meta_is-feature', 'true')
            ->orderBy('meta_list_place', 'asc')
            ->with('user:id,name')->with('category:id,name')->get();

        $resources['articles'] = $articles;
        $resources['currently_featured'] = $currently_featured;

        return Response::json([
            'resources' => $resources
        ], 200);
    }
    public function save_featured_order(Request $request)
    {
        // if (Auth::user()->cant('update', Article::class)) {
        //     return Response::json([
        //         'msg' => 'Bad request. Unathorized.',
        //     ], 401);
        // }
        $articles_ids = $request->all();

        $articles = [];
        if (is_array($articles_ids)) {
            //remove all featured articles
            Article::where('meta_is-feature', 'true')->update(['meta_is-feature' => 'false', 'meta_list_place' => null]);


            $selected_articles_with_order = Article::whereIn('id', $articles_ids)->get();


            foreach ($selected_articles_with_order as $order_place => $article) {



                $article['meta_is-feature'] = 'true';
                $article['meta_list_place'] = array_search($article->id, $articles_ids) + 1;
                $article->save();
                activity()->performedOn($article)->log('changed_order');
            }

            $articles = $selected_articles_with_order;

            // $articles = Article::whereIn('id', $articles_ids)->update(['meta_is-feature' => true, 'meta_list_place' => null]);

            return Response::json([
                'request' => $articles->map->only(['id', 'meta_is-feature', 'meta_list_place'])
            ], 200);
        }


        return Response::json([
            'msg' => 'Bad request. Expected array.',
        ], 403);
    }
    public function activity_log(Request $request)
    {
        $validated = $request->all();


        $query = Activity::select(['id', 'description', 'causer_id', 'causer_type', 'subject_id', 'subject_type', 'created_at', 'properties'])->with('causer:id,name,role');



        if (isset($validated['date_start'])) {

            $date = Carbon::parse($validated['date_start'])->toDateTimeString();
            $query = $query->where('created_at', '>=', $date);
        }

        if (isset($validated['date_end'])) {
            $date = Carbon::parse($validated['date_end'])->setTimeFrom(Carbon::now())->toDateTimeString();
            $query = $query->where('created_at', '<=', $date);
        }


        if (isset($validated['model'])) {
            if ($validated['model'] === 'comment') {
                $query = $query->where('subject_type', 'App\\Comment');
            }
            if ($validated['model'] === 'article') {
                $query = $query->where('subject_type', 'App\\Article');
            }
            if ($validated['model'] === 'tag') {
                $query = $query->where('subject_type', 'App\\Tag');
            }
            if ($validated['model'] === 'category') {
                $query = $query->where('subject_type', 'App\\Category');
            }
            if ($validated['model'] === 'user') {
                $query = $query->where('subject_type', 'App\\User');
            }
        }

        $activity = $query->orderBy('created_at', 'desc')->get();



        return Response::json([
            'resource' => $activity,
        ], 200);
    }
}
