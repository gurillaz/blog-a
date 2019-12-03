<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Exports\ArticlesExport;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Image;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Article::query()
            ->withTrashed()
            ->with('user:id,name')
            ->with('category:id,name')
            ->withCount('comments')
            ->paginate(10);






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

        $data_autofill['tags'] = Tag::all('id', 'name');
        $data_autofill['categories'] = Category::all('id', 'name');



        return Response::json([
            'data_autofill' => $data_autofill,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateArticleRequest $request)
    {
        // return $request['image'];
        $validated = $request->validated();
        // return $validated['tags'];
        $article = new Article();

        $article->title = $validated['title'];
        $article->slug = Str::slug($article->title . ' ' . Auth::user()->name . ' ', '-');
        $article->body = $validated['body'];
        $article->summary = $validated['summary'];
        $article->publishing_date = Carbon::now();
        $article->category_id = $validated['category_id'];
        $article->meta_status = 'published';
        $article->user_id = Auth::id();








        // Thumbnail
        $thumbnail = Image::Make($validated['image'])
            ->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(storage_path('app') . "\\public\\images\\" . Str::slug($article->title . ' ' . Auth::user()->name . ' ', '-') . "_thumbnail.png");

        $image = Image::make($validated['image']);
        $watermark_mask = Image::make(file_get_contents(public_path() . '\\watermark_mask.png'));

        $image->insert($watermark_mask, "center")
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(storage_path('app') . "\\public\\images\\" . Str::slug($article->title . ' ' . Auth::user()->name . ' ', '-') . ".jpg");


        $article->image_path = Str::slug($article->title . ' ' . Auth::user()->name . ' ', '-') . ".jpg";


        $article->save();

        //Savin tags
        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }
        return Response::json([
            'resource_slug' => $article->slug,
            'msg' => "Article submited for review.",
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {


        $this->authorize('view', $article);

        $resource = [];



        $resource = $article;
        $resource['user'] = $article->user()->first(['id', 'name']);
        $resource['category'] = $article->category()->first(['id', 'name']);

        $resource['tags'] = $article->tags()->get(['id', 'name']);


        return Response::json([
            'resource' => $resource,
        ], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show_slug($slug)
    {
        $article = Article::where('slug', $slug)->first();


        if ($article == null) {
            return Response::json([
                'msg' => "Resource not found"
            ], 404);
        }



        $this->authorize('view', $article);

        $resource = [];



        $resource = $article;
        $resource['user'] = $article->user()->first(['id', 'name']);
        $resource['category'] = $article->category()->first(['id', 'name']);

        $resource['comments'] = $article->comments()->with("user:id,name,role")->with('replies.user')->get();

        $resource['tags'] = $article->tags()->get(['id', 'name']);


        return Response::json([
            'resource' => $resource,
        ], 200);

        // $article = Article::where('slug',$slug);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $this->authorize('edit', $article);

        $resource = [];
        $resource_relations = [];
        $data_autofill = [];


        $resource = $article;
        $resource['tags'] = $article->tags()->pluck('id');

        $resource_relations['user'] = $article->user->only(['id', 'name']);
        $resource_relations['category'] = $article->category;


        $data_autofill['tags'] = Tag::all('id', 'name');
        $data_autofill['categories'] = Category::all('id', 'name');


        return Response::json([
            'resource' => $resource,
            'resource_relations' => $resource_relations,
            'data_autofill' => $data_autofill
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {

        $validated = $request->validated();



        $article->title = $validated['title'];
        $article->slug = Str::slug($article->title . ' ' . Auth::user()->name . ' ', '-');
        $article->body = $validated['body'];
        $article->summary = $validated['summary'];
        $article->publishing_date = Carbon::now();
        $article->category_id = $validated['category_id'];
        $article->meta_status = 'published';
        $article->user_id = Auth::id();

        if (isset($validated['image'])) {

            // Thumbnail
            $thumbnail = Image::Make($validated['image'])
                ->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(storage_path('app') . "\\public\\images\\" . Str::slug($article->title . ' ' . Auth::user()->name . ' ', '-') . "_thumbnail.png");

            $image = Image::make($validated['image']);
            $watermark_mask = Image::make(file_get_contents(public_path() . '\\watermark_mask.png'));

            $image->insert($watermark_mask, "center")
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(storage_path('app') . "\\public\\images\\" . Str::slug($article->title . ' ' . Auth::user()->name . ' ', '-') . ".jpg");


            $article->image_path = Str::slug($article->title . ' ' . Auth::user()->name . ' ', '-') . ".jpg";
        }

        $article->save();

        //Savin tags
        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }
        return Response::json([
            'resource_slug' => $article->slug,
            'msg' => "Article updated.",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
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
        $article->save();



        activity()->performedOn($article)->log('aproved');

        return Response::json([
            'msg' => "success"
        ], 200);
    }
    public function deny_article(Article $article)
    {
        //TODO set this to denied
        $article->meta_status = "deleted";
        $article->publishing_date = NULL;
        $article->save();

        activity()->performedOn($article)->log('denied');



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
            $article->save();
        };

        activity()->log('aproved_all_articles');



        return Response::json([
            'msg' => "success"
        ], 200);
    }


    public function export(Request $request)
    {
        // return "OKKKKK";

        $validated = $request->all();


        $date_start = "";
        $date_end = "";
        if (isset($validated['date_start'])) {

            $date_start = Carbon::parse($validated['date_start'])->toDateTimeString();
        }

        if (isset($validated['date_end'])) {
            $date_end = Carbon::parse($validated['date_end'])->setTimeFrom(Carbon::now())->toDateTimeString();
        }

        if (isset($validated['type'])) {
            $file_name = 'articles_' . Carbon::now()->toDateTimeString();
            if ($validated['type'] == 'csv') {
                return (new ArticlesExport($date_start, $date_end))->download($file_name . '.csv', \Maatwebsite\Excel\Excel::CSV);
            } else {

                return (new ArticlesExport($date_start, $date_end))->download($file_name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            }
        }

        return "File genereation failed. Try again!";
    }
}
