<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Mail\CommentAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{

    public function store(Request $request, Post $post)
    {

        $validatedData = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = $post->comments()->create([
            'content' => $validatedData['content'],
            'user_id' => auth()->user()->id,

        ]);


        Mail::to($post->user->email)->send(new CommentAdded($comment));

        return redirect()->route('posts.home')->with('success', 'Comment Added Successfully');
    }

    public function delete(Comment $comment)
    {
        if (auth()->id() == $comment->user_id) {

            $comment->delete();

            return redirect()->back()->with('success', 'Comment deleted successfully.');
        }
    }
}
