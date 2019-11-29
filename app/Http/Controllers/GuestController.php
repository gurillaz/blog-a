<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Comment;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class GuestController extends Controller
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
    public function data_autofill()
    {
        $data_autofill = [];

        $data_autofill['categories'] = Category::all(['id', 'name']);
        $data_autofill['tags'] = Tag::all(['id', 'name']);
        $data_autofill['users'] = User::all(['id', 'name']);




        return Response::json([
            'data_autofill' => $data_autofill,
        ], 200);
    }



    public function get_results(Request $request)
    {
        $validated = $request;
        $query = Article::select([
            'articles.id', 'slug', 'summary', 'title', 'image_path', 'publishing_date', 'category_id', 'articles.user_id',
            'users.name as user_name', 'categories.name as category_name'
        ])


            ->join('users', 'users.id', '=', 'articles.user_id')
            ->join('categories', 'categories.id', '=', 'articles.category_id')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments');
        // ->where('meta_status', 'published');
        if ($validated->get('search_term') != null) {
            $query = $query->where('title', 'LIKE', '%' . $validated->get('search_term') . '%');
            $query = $query->where('body', 'LIKE', '%' . $validated->get('search_term') . '%');
            $query = $query->where('summary', 'LIKE', '%' . $validated->get('search_term') . '%');
        }
        if ($validated->get('user_id') != null) {
            $query = $query->where('articles.user_id', $validated->get('user_id'));
        }
        if ($validated->get('category_id') != null) {
            $query = $query->where('articles.category_id', $validated->get('category_id'));
        }
        if ($validated->get('date_start') != null) {
            $date = Carbon::parse($validated->get('date_start'))->toDateTimeString();
            $query = $query->where('publishing_date', '>=', $date);
        }

        if ($validated->get('date_end') != null) {
            $date = Carbon::parse($validated->get('date_end'))->toDateTimeString();
            $query = $query->where('publishing_date', '<=', $date);
        }
        if ($validated->get('tags') != null && is_array($validated->get('tags'))) {
            $tags = $validated->get('tags');
            $query = $query->whereHas('tags', function (Builder $q) use ($tags) {
                $q->whereIn('id', $tags);
            });
        }


        if ($validated->get('sort_by') != null) {
            if ($validated->get('sort_by') == 'title') {
                $query->orderBy('title', 'desc');
            } elseif ($validated->get('sort_by') == 'publishing_date') {
                $query->orderBy('publishing_date', 'desc');
            } elseif ($validated->get('sort_by') == 'comments_count') {
                $query->orderBy('comments_count', 'desc');
            } elseif ($validated->get('sort_by') == 'user_name') {
                $query->orderBy('user_name', 'asc');
            } elseif ($validated->get('sort_by') == 'category_name') {
                $query->orderBy('category_name', 'asc');
            } else {
                $query->orderBy('title', 'desc');
            }
        }
        $results = $query->paginate(10);



        return Response::json([
            'results' => $results,
        ], 200);
    }
}
