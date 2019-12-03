<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Comment;
use App\Http\Requests\SearchRequest;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class GuestController extends Controller
{
    public function home()
    {
        $main_featured = Article::select(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])

            ->where('meta_list_place', 1)
            ->where('meta_status', 'published')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')
            ->get();
        $three_main_featured = Article::select(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])

            ->whereIn('meta_list_place', [2, 3, 4])
            ->where('meta_status', 'published')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')
            ->orderBy('meta_list_place', 'desc')

            ->get();

        $others_featured = Article::select(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])

            ->where('meta_list_place', '>', 4)
            ->where('meta_status', 'published')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments')
            ->orderBy('meta_list_place', 'desc')
            ->take(5)
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
        $resources['main_featured'] = $main_featured;
        $resources['three_main_featured'] = $three_main_featured;
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



    public function get_search_results(SearchRequest $request)
    {
        $validated = $request->validated();
        $query = Article::select([
            'articles.id', 'slug', 'summary', 'title', 'image_path', 'publishing_date', 'category_id', 'articles.user_id',
            'users.name as user_name', 'categories.name as category_name'
        ])

            ->where('meta_status', 'published')

            ->join('users', 'users.id', '=', 'articles.user_id')
            ->join('categories', 'categories.id', '=', 'articles.category_id')
            ->with(['category:id,name', 'user:id,name'])
            ->withCount('comments');
        // ->where('meta_status', 'published');
        if (isset($validated['search_term'])) {
            $query = $query->where('title', 'LIKE', '%' . $validated['search_term'] . '%');
            $query = $query->orWhere('body', 'LIKE', '%' . $validated['search_term'] . '%');
            $query = $query->orWhere('summary', 'LIKE', '%' . $validated['search_term'] . '%');
        }
        if (isset($validated['user_id'])) {

            $query = $query->where('articles.user_id', $validated['user_id']);
        }
        if (isset($validated['category_id'])) {

            $query = $query->where('articles.category_id', $validated['category_id']);
        }
        if (isset($validated['date_start'])) {
            $date = Carbon::parse($validated['date_start'])->toDateTimeString();
            $query = $query->where('publishing_date', '>=', $date);
        }

        if (isset($validated['date_end'])) {
            $date_end = Carbon::parse($validated['date_end'])->setTimeFrom(Carbon::now())->toDateTimeString();
            $query = $query->where('publishing_date', '<=', $date);
        }
        if (isset($validated['tags'])) {

            $tags = $validated['tags'];
            $query = $query->whereHas('tags', function (Builder $q) use ($tags) {
                $q->whereIn('id', $tags);
            });
        }


        if (isset($validated['sort_by'])) {

            if ($validated['sort_by'] == 'title') {
                $query->orderBy('title', 'asc');
            } elseif ($validated['sort_by'] == 'publishing_date') {
                $query->orderBy('publishing_date', 'desc');
            } elseif ($validated['sort_by'] == 'comments_count') {
                $query->orderBy('comments_count', 'desc');
            } elseif ($validated['sort_by'] == 'user_name') {
                $query->orderBy('user_name', 'asc');
            } elseif ($validated['sort_by'] == 'category_name') {
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
