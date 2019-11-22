<?php

use App\Article;
use App\Category;
use App\Comment;
use App\Tag;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        if (User::query()->where('id', '1')->first()) { } else {
            $admin = new User();

            $admin->name = "Klevis Sahitaj";
            $admin->email = "klevissahitaj@gmail.com";
            $admin->role = "admin";
            $admin->email_verified_at = now();
            $admin->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';


            $admin->save();
        }

        factory('App\User', 5)->create(['role' => 'admin']);
        factory('App\User', 10)->create(['role' => 'client']);

        $users = User::all('id');

        factory('App\Category', 12)->create(['user_id' => $users->random()->id]);
        factory('App\Tag', 20)->create(['user_id' => $users->random()->id]);

        $categories = Category::all('id');
        $tags = Tag::all('id');

        factory('App\Article', 30)->make()
        ->each(function ($article) use ($users, $tags,$categories) {
            $article->user_id = $users->random()->id;
            $article->category_id = $categories->random()->id;

            $article->save();
            
            $article->tags()->sync($tags->random(5));

        });        
        
        
        $articles = Article::all();



        factory('App\Comment', 50)->make()
        ->each(function ($comment) use ($users, $articles) {
            $comment->user_id = $users->random()->id;
            $comment->commentable_type= Article::class;
            $comment->commentable_id = $articles->random()->id;
            $comment->save();
        });



        $comments = Comment::all();


        factory('App\Comment', 100)->make()
        ->each(function ($comment) use ($users, $comments) {
            $comment->user_id = $users->random()->id;
            $comment->commentable_type= Comment::class;
            $comment->commentable_id = $comments->random()->id;
            $comment->save();
        });


        // $comments->each(function ($comment) use ($comments) {
        //     if(rand(0,1) == 1){
        //     $comment->parent_id = $comments->random()->id;
        //     }
        // });


        // $comments->each(function ($comment) use ($comments) {
        //     if(rand(0,1) == 1){
        //     $comment->parent_id = $comments->random()->id;
        //     }
        // });


        


    }
}
