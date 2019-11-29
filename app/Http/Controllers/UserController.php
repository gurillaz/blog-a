<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    //
    public function index()
    {

        $users = User::query()->withCount('comments')->withCount('articles')->paginate(10);
        return response()->json(
            [
                'data' => $users,
            ],
            200
        );
    }

    private function admin_show_user(User $user)
    {
        $resource = [];


        $resource = $user;
        $resource['comments'] = $user->comments()
            ->withTrashed()

            ->get()->map(function ($comment) {
                if ($comment->commentable_type == Comment::class) {
                    // $comment['article'] = $comment->commentable()->first('commentable_id')['commentable_id'];
                    $comment['article'] = Article::where('id', $comment->commentable()->first('commentable_id')['commentable_id'])->first(['id', 'slug', 'title']);
                } else {
                    $comment['article'] = $comment->commentable()->first(['id', 'slug', 'title']);
                }
                return $comment;
            });


        $articles = $user->articles(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id', 'meta_status'])
            ->withTrashed()
            ->withCount('comments')

            ->with(['category:id,name', 'user:id,name'])

            ->orderBy('publishing_date', 'desc')
            ->get();


        $resource['articles'] = $articles;


        return Response::json([
            'resource' => $resource,
            // 'resource_relations' => $resource_relations,
        ], 200);
    }

    public function show(User $user)
    {

        if (Auth::check() && Auth::user()->role == 'admin') {
            return $this->admin_show_user($user);
        }
        $resource = [];
        $resource_relations = [];


        $resource = $user;
        $resource_relations['comments'] = $user->comments()
            ->where('meta_status', 'published')
            ->get()->map(function ($comment) {
                if ($comment->commentable_type == Comment::class) {
                    // $comment['article'] = $comment->commentable()->first('commentable_id')['commentable_id'];
                    $comment['article'] = Article::where('id', $comment->commentable()->first('commentable_id')['commentable_id'])->first(['id', 'slug', 'title']);
                } else {
                    $comment['article'] = $comment->commentable()->first(['id', 'slug', 'title']);
                }
                return $comment;
            });

        $latest = $user->articles(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])
            ->where('meta_status', 'published')
            ->with(['category:id,name', 'user:id,name'])
            ->orderBy('publishing_date', 'desc')->take(1)
            ->get();
        if ($latest->count() != 0) {
            $articles = $user->articles(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])
                ->where('meta_status', 'published')

                ->with(['category:id,name', 'user:id,name'])
                ->orderBy('publishing_date', 'desc')
                ->get();
        } else {
            $articles = $user->articles(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])
                ->with(['category:id,name', 'user:id,name'])
                ->where('meta_status', 'published')

                ->orderBy('publishing_date', 'desc')
                ->get();
        }

        $resource_relations['articles'] = $articles;
        $resource_relations['latest_article'] = $latest;

        return Response::json([
            'resource' => $resource,
            'resource_relations' => $resource_relations,
        ], 200);
    }

    public function auth_user()
    {
        $user = Auth::user();

        $resource = [];
        $resource_relations = [];


        $resource = $user;
        $resource_relations['comments'] = $user->comments()
            ->withTrashed()

            ->get()->map(function ($comment) {
                if ($comment->commentable_type == Comment::class) {
                    // $comment['article'] = $comment->commentable()->first('commentable_id')['commentable_id'];
                    $comment['article'] = Article::where('id', $comment->commentable()->first('commentable_id')['commentable_id'])->first(['id', 'slug', 'title']);
                } else {
                    $comment['article'] = $comment->commentable()->first(['id', 'slug', 'title']);
                }
                return $comment;
            });

        $latest = $user->articles(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])
            ->withTrashed()
            ->with(['category:id,name', 'user:id,name'])
            ->orderBy('publishing_date', 'desc')->take(1)
            ->get();
        if ($latest->count() != 0) {
            $articles = $user->articles(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])
                ->withTrashed()


                ->with(['category:id,name', 'user:id,name'])
                ->orderBy('publishing_date', 'desc')
                ->get();
        } else {
            $articles = $user->articles(['id', 'slug', 'summary', 'title', 'image_path', 'category_id', "publishing_date", 'user_id'])
                ->withTrashed()

                ->with(['category:id,name', 'user:id,name'])

                ->orderBy('publishing_date', 'desc')
                ->get();
        }

        $resource_relations['articles'] = $articles;
        $resource_relations['latest_article'] = $latest;

        return Response::json([
            'resource' => $resource,
            'resource_relations' => $resource_relations,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        foreach ($user->articles()->get() as $article) {
            foreach ($article->comments()->get() as $comment) {

                foreach ($comment->replies()->get() as $reply) {
                    $reply->delete();
                    $reply->save();
                }
                $comment->delete();
                $comment->save();
            }
            $article->delete();
            $article->save();
        }
        foreach ($user->comments()->get() as $comment) {
            $comment->body = "[deleted]";
            $comment->user_id = NULL;
            $comment->save();
        }

        $user->delete();
        $user->save();


        return Response::json([
            'msg' => 'User deleted'
        ], 200);
    }
}
