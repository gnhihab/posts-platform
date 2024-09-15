<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostFeatureTest extends TestCase
{
    /**
     * @test
     */

    // use RefreshDatabase;

    //Create

    public function authanicatedUsersCanCreatePosts()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->postJson('/api/post/create',[
            "title"=>"Test Title",
            "content"=>"Test Content",
        ]);

        $response->assertStatus(201);
        $response-> assertJson([
            'status' => true ,
            'message' => 'Post Created Successfully',
        ]);
    }

        public function unauthanicatedUsersCannotCreatePosts()
    {
        $response = $this->postJson('/api/post/create',[
            'title' => "New Title",
            'content' => "New Content",
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => "Unauthenticated."
        ]);

    }

    //Update

    public function authanicatedUsersCanUpdatePosts()
    {

        $user = User::factory()->create();
        $post = Post::factory()->create([
            "user_id" => $user->id,
            "title" => "Old Test Title",
            "content" => "Old Test Content",
        ]);

        Sanctum::actingAs($user);
        $response = $this->putJson("/api/post/update/{$post->id}", [
            "title" => "Update Test Title",
            "content" => "Update Test Content",
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            "status" => true,
            "message" => "Post Updated Successfully",
        ]);
    }

    public function unauthanicatedUsersCannotUpdatePosts()
    {
        $post = Post::factory()->create([
            "title"=>"Post Title",
            "content"=>"Post Content",
        ]);

        $response = $this->putJson("/api/post/update/{$post->id}",[
            "title"=>"Update Title",
            "content"=>"Update Content",
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            "message"=>"Unauthenticated.",
        ]);

    }

    public function usersCanOnlyUpdateTheirPosts()
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $post = Post::factory()->create([
            "title"=>"Post Title",
            "content"=>"Post Content"
        ]);

        Sanctum::actingAs($user);

        $response = $this->putJson("/api/post/update/{$post->id}",[
            "user_id"=>$owner->id,
            "title"=>"Update Title",
            "content"=>"Update Content",
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            "status"=>false,
            "message"=>"You Only Can Edit Your Posts",
        ]);
    }

    public function UpdatedPostNotFound()
    {
        $owner = User::factory()->create();
        $nonExistID = 0;

        Sanctum::actingAs($owner);

        $response = $this->putJson("/api/post/update/{$nonExistID}",[
            "title"=>"Update Title",
            "content"=>"Update Content",
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            "status"=>false,
            "message"=>"Post Not Found"
        ]);

    }

    //Delete

    public function authanicatedUsersCanDeletePosts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            "user_id" => $user->id,
            "title" => "Test Data",
            "content" => "Test Content"
        ]);

        Sanctum::actingAs($user);
        $response = $this->deleteJson("/api/post/delete/{$post->id}");

        $response->assertStatus(201);
        $response->assertJson([
            "status" => true,
            "message" => "Post Deleted Successfully",
        ]);
    }



    public function usersCanOnlyDeleteTheirPosts()
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();

        Sanctum::actingAs($user);

        $post = Post::factory()->create([
            "user_id"=>"{$owner->id}",
            "title"=>"Post Title",
            "content"=>"Post Content",
        ]);

        $response = $this->deleteJson("/api/post/delete/{$post->id}");

        $response->assertStatus(403);
        $response->assertJson([
            "status"=>false,
            "message"=>"You Only Can Delete Your Posts",
        ]);
    }

    public function DeletePostNotFound()
    {
        $owner = User::factory()->create();
        Sanctum::actingAs($owner);
        $nonExistID = 0;

        $response = $this->deleteJson("/api/post/delete/{$nonExistID}");

        $response->assertStatus(404);
        $response->assertJson([
            "status"=>false,
            "message"=>"Post Not Found"
        ]);

    }

}
