<?php

namespace App\Providers;

use App\Article;
use App\Category;
use App\Comment;
use App\Policies\ArticlePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\ResourcePolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use App\Tag;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Article::class => ArticlePolicy::class,
        Comment::class => CommentPolicy::class,
        Tag::class => TagPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
