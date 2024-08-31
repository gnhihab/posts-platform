<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Mail\CommentAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ApiCommentController extends Controller
{

    public function store(Request $request, Post $post)
    {
        $validateData = Validator::make(
            $request->all(),
            [
                'content' => 'required|string|max:255',
            ]
        );

        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                "errors" => $validateData->errors()
            ], 401);
        }

        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        Mail::to($post->user->email)->send(new CommentAdded($comment));

        return response()->json([
            'status' => true,
            "message" => 'Comment Added Successfully',
            "comment" => $comment->content
        ], 201);
    }

    public function delete($id)
    {
        $comment = Comment::find($id);

        if ($comment == null) {
            return response()->json([
                'status' => false,
                "message" => 'Comment Id Not Found'
            ], 404);
        }
        if ($comment->user_id !== Auth::id()) {

            return response()->json([
                'status' => false,
                "message" => 'You Can Only Delete Your Comments'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => true,
            "message" => 'Comment Deleted Successfully'
        ], 201);
    }
}
