<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(5)->create();
        Tag::factory(20)->create();

        User::factory(1)->create([
            'name' => 'Admin User',
            'role' => 'admin',
            'email' => 'admin.user@example.com'
        ]);

        User::factory(3)
            ->create()
            ->each(function ($user) {

            Storage::copy('/public/layouts/default-avatar.jpg', 'public/avatars/default-avatar.jpg');

            Post::factory(20)
                ->create(['user_id' => $user->id, 'category_id' => rand(1,5)])
                ->each(function ($post) {

                $tags = range(1,20);
                shuffle($tags);
                $tags = array_slice($tags, 0, 3);

                $post->tags()->attach($tags);
                    
                Storage::copy('/public/layouts/default-preview.jpg', 'public/previews/default-preview.jpg');

                Comment::factory(5)
                ->create(['user_id' => rand(1, $post->user->id), 'post_id' => $post->id])
                ->each(function ($comment) {
                    CommentLike::factory(rand(0,1))->create(['user_id' => $comment->post->user->id, 'comment_id' => $comment->id]);
                });

                PostLike::factory(rand(0,1))->create(['user_id' => $post->user->id, 'post_id' => $post->id]);
            });
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
