<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Blog;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create();

        // Create some blogs for the user
        Blog::factory(5)->create(['user_id' => $user->id])->each(function ($blog) use ($user) {

            // Create some posts for each blog
            Post::factory(3)->create(['blog_id' => $blog->id])->each(function ($post) use ($user) {

                // Create likes for each post
                Like::factory(5)->create(['post_id' => $post->id, 'user_id' => $user->id]);

                // Create comments for each post
                Comment::factory(3)->create(['post_id' => $post->id, 'user_id' => $user->id]);
            });
        });
    }
}
