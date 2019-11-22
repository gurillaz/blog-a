<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;

use Illuminate\Database\Eloquent\Builder;

class SearchController extends Controller
{
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
        $query = Article::select(['articles.id', 'slug', 'summary', 'title', 'image_path','publishing_date', 'category_id', 'articles.user_id',
             'users.name as user_name', 'categories.name as category_name'])

             
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
