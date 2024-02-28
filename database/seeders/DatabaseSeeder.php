<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
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
                ->create(['user_id' => $user->id, 'category_id' => rand(1,3)])
                ->each(function ($post) {

                $post->tags()->attach([rand(1,5), rand(6,14), rand(15,20)]);
                    
                Storage::copy('/public/layouts/default-preview.avif', 'public/previews/default-preview.avif');

                Comment::factory(5)
                ->create(['user_id' => rand(1, $post->user->id), 'post_id' => $post->id]);

                Like::factory(rand(0,20))->create(['user_id' => rand(1, $post->user->id), 'post_id' => $post->id]);
            });
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
