<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('CheckIsAdminOrSelf', ['only' => ['edit', 'delete']]);
    // }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::query()
            ->withTrashed()
            ->with('user:id,name')

            ->paginate(10);

        //https://laravel.com/docs/5.4/collections#method-transform
        //https://gist.github.com/Repox/7784159810681db92b87ca44d5a9464d
        $comments->getCollection()->transform(function ($comment) {
            if ($comment->commentable_type == Comment::class) {
                // $comment['article'] = $comment->commentable()->first('commentable_id')['commentable_id'];
                $comment['article'] = Article::where('id', $comment->commentable()->first('commentable_id')['commentable_id'])->first(['id', 'slug', 'title']);
            } else {
                $comment['article'] = $comment->commentable()->first(['id', 'slug', 'title']);
            }
            return $comment;
        });


        return response()->json(
            [
                'data' => $comments,
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
    public function store(CreateCommentRequest $request)
    {
        $validated = $request->validated();
        if ($validated['user_id'] != Auth::id()) {
            return Response::json([
                'message' => "Not authorized! Forbiden!"
            ], 403);
        }
        //        return $validated;
        $comment = new Comment();

        $comment->body = $validated['body'];
        $comment->meta_status = 'published';
        $comment->user_id = Auth::id();
        $comment->commentable_type = $validated['commentable_type'];
        $comment->commentable_id = $validated['commentable_id'];

        $comment->save();
        $comment->user;
        $comment['replies'] = [];


        return Response::json([
            'comment' => $comment,
            'message' => "Comment added!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($comment_id)
    {
        $comment = Comment::withTrashed()->find($comment_id);

        if ($comment == null) {
            return Response::json([
                'message' => 'Resource not found',
            ], 404);
        }



        $this->authorize('view', $comment);

        $resource = [];



        $resource = $comment;
        $resource['user'] = $comment->user()->first(['id', 'name']);



        return Response::json([
            'resource' => $resource,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $validated = $request->validated();

        $comment->body = $validated['body'];
        $comment->save();

        $comment->user;
        $comment['replies'] = [];


        return Response::json([
            'comment' => $comment,
            'message' => "Comment updated!"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->user_id = NULL;
        $comment->body = "[deleted]";
        $comment->meta_status = 'deleted';
        $comment->save();

        return Response::json([
            'message' => "Comment deleted!",
            'comment' => $comment
        ], 200);
    }
}
