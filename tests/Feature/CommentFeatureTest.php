<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentFeatureTest extends TestCase
{
    /**
     *@test
     */

    // use RefreshDatabase;

    // public function UsersCanCreateComments()
    // {
    //     $user = User::factory()->create();
    //     $post = Post::factory()->create();

    //     Sanctum::actingAs($user);

    //     $resonse = $this->postJson("api/comment/create/{$post->id}",[
    //         "post_id"=>$post->id,
    //         "content"=>"Comment Content",
    //     ]);

    //     $resonse->assertStatus(201);
    //     $resonse->assertJson([
    //         "status"=>true,
    //         "message"=>"Comment Added Successfully",
    //         "comment"=>"Comment Content",
    //     ]);

    // }

    // public function userCanDeleteComments()
    // {
    //     $user = User::factory()->create();
    //     $post = Post::factory()->create();
    //     $comment = Comment::factory()->create([
    //         "user_id"=>$user->id,
    //         "post_id"=>$post->id,
    //         "content"=>"Commet Content",
    //     ]);

    //     Sanctum::actingAs($user);

    //     $response = $this->deleteJson("/api/comment/delete/{$comment->id}");

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         "status"=>true,
    //         "message"=>"Comment Deleted Successfully",
    //     ]);
    // }

    // public function usersCanOnlyDeleteTheirComments()
    // {
    //     $user = User::factory()->create();
    //     $owner = User::factory()->create();
    //     $post = Post::factory()->create();
    //     Sanctum::actingAs($user);
    //     $comment = Comment::factory()->create([
    //         "user_id"=>$owner->id,
    //         "post_id"=>$post->id,
    //         "content"=>"Comment Content",
    //     ]);

    //     $response = $this->deleteJson("/api/comment/delete/{$comment->id}");
    //     $response->assertStatus(403);
    //     $response->assertJson([
    //         "status"=>false,
    //         "message"=>"You Can Only Delete Your Comments"
    //     ]);

    // }

    public function DeleteCommentNotFound()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);
        $nonExistID=0;
        
        $response = $this->deleteJson("/api/comment/delete/{$nonExistID}");
        $response->assertStatus(404);
        $response->assertJson([
            "status"=>false,
            "message"=>"Comment Id Not Found",
        ]);
    }

}
