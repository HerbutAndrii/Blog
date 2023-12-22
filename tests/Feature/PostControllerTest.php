<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

     private $user;
     private $post;
     private $category;
     private $tags;
     private $comments;

    public function setUp(): void
    {
        parent::setUp();

        session()->start();

        $this->user = User::factory()->create();

        $this->category = Category::factory()->create();

        $this->tags = Tag::factory(3)->create();

        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $this->post->tags()->attach($this->tags);

        $this->comments = Comment::factory(5)->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id
        ]);
    }

    public function test_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('post.index'));

        $response->assertStatus(200);

        $response->assertSeeText('All posts');

        $this->assertNotNull($response->original->getData()['posts']);
    }

    public function test_userIndex(): void
    {
        $response = $this->actingAs($this->user)->get(route('post.user.index'));

        $response->assertStatus(200);

        $response->assertSeeText('My posts');

        $this->assertNotNull($response->original->getData()['posts']);
    }

    public function test_show(): void
    {
        $response = $this->actingAs($this->user)->get(route('post.show', $this->post));

        $response->assertStatus(200);

        $response->assertSeeText(str($this->post->title), str($this->post->category));

        $response->assertViewHasAll(['post', 'date', 'relatedPosts', 'like']);

        $this->assertNotNull($response->original->getData()['post']);
    }

    public function test_create(): void
    {
        $response = $this->actingAs($this->user)->get(route('post.create'));

        $response->assertStatus(200);

        $response->assertSeeText('Create post');

        $this->assertNotNull($response->original->getData()['tags']);

        $this->assertNotNull($response->original->getData()['categories']);
    }

    public function test_store(): void
    {
        $file = UploadedFile::fake()->image('preview.jpg');

        $response = $this->actingAs($this->user)->post(route('post.store'), [
            '_token' => csrf_token(),
            'title' => 'Test title',
            'content' => 'Test content',
            'category' => $this->category->name,
            'preview' => $file,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test title',
            'content' => 'Test content',
        ]);

        $this->assertTrue(Storage::exists('public/previews/' . $file->hashName()));
    }

    public function test_edit(): void
    {
        $response = $this->actingAs($this->user)->get(route('post.edit', $this->post));

        $response->assertStatus(200);

        $response->assertSeeText('Edit post');

        $this->assertNotNull($response->original->getData()['post']);

        $this->assertNotNull($response->original->getData()['tags']);

        $this->assertNotNull($response->original->getData()['categories']);
    }

    public function test_update(): void
    {
        $file = UploadedFile::fake()->image('new-preview.jpg');

        $response = $this->actingAs($this->user)->put(route('post.update', $this->post), [
            '_token' => csrf_token(),
            'title' => 'New title',
            'content' => 'New content',
            'category' => 'New category',
            'preview' => $file,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('posts', [
            'title' => 'New title',
            'content' => 'New content',
        ]);

        $this->assertTrue(Storage::exists('public/previews/' . $file->hashName()));
    }

    public function test_destroy(): void
    {
        $response = $this->actingAs($this->user)->delete(route('post.destroy', $this->post), [
            '_token' => csrf_token()
        ]);

        $response->assertStatus(302);

        $this->assertModelMissing($this->post);
    }
}
