<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Tag::query()
            // ->withTrashed()
            ->with('user:id,name')
            ->withCount('articles')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Response::json(
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
    public function store(CreateTagRequest $request)
    {
        $validated = $request->validated();


        $tag = new Tag();
        $tag->name = $validated['name'];
        $tag->description = $validated['description'];
        $tag->user_id = Auth::user()->id;
        $tag->save();



        $tag['user'] = $tag->user()->first(['id', 'name']);
        $tag['articles_count'] = 0;

        return Response::json([
            'resource' => $tag,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $resource = [];
        $resource_relations = [];
        $data_autofill = [];


        $resource = $tag;
        $resource_relations['articles'] = $tag->articles()->with("user:id,name,role")->with("category:id,name")->get();


        return Response::json([
            'resource' => $resource,
            'resource_relations' => $resource_relations,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $validated = $request->validated();
        $tag->name = $validated['name'];
        $tag->description = $validated['description'];
        $tag->save();
        return Response::json([
            'msg' => 'sucess',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->articles()->detach([]);
        $tag->delete();

        return Response::json([
            'msg' => 'success',
        ], 200);
    }
}
