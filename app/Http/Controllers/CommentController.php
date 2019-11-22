<?php

namespace App\Http\Controllers;

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
        //
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
    public function show(Comment $comment)
    {
        //
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
        $comment->save();
        return Response::json([
            'message' => "Comment deleted!"
        ], 200);
    
    }
}
