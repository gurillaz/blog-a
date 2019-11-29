<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Category::query()->with('user:id,name')->withCount('articles')->paginate(10);
        return response()->json(
            [
                'data' => $resources,
            ],
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $validated = $request->validated();


        $category = new Category();
        $category->name = $validated['name'];
        $category->description = $validated['description'];
        $category->user_id = Auth::user()->id;
        $category->save();


        $category['user'] = $category->user()->first(['id', 'name']);
        $category['articles_count'] = 0;

        return Response::json([
            'resource' => $category,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $resource = [];
        $resource_relations = [];
        $data_autofill = [];


        $resource = $category;
        $resource_relations['articles'] =
            $category->articles(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', 'user_id'])
            ->with("user:id,name,role")->withCount('comments')->get();


        return Response::json([
            'resource' => $resource,
            'resource_relations' => $resource_relations,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $category->name = $validated['name'];
        $category->description = $validated['description'];
        $category->save();
        return Response::json([
            'msg' => 'sucess',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        foreach ($category->articles as $article) {
            foreach ($article->comments as $comment) {
                $comment->replies()->delete();
                $comment->delete();
            }
            $article->meta_status = "deleted";
            $article->delete();
            $article->save();   
        }
        // $category->articles()->comments()->delete();
        // $category->articles()->delete();

        $category->delete();
        $category->save();

        return Response::json([
            'msg' => 'success',
        ], 200);
    }
}
