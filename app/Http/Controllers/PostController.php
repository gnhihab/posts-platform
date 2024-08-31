<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $posts = Post::all();
        return view('Posts.home',compact("posts"));

    }

    public function show($id)
    {

        $post = Post::FindOrFail($id);
        return view('Posts.Show',compact('post'));

    }

    public function create()
    {

        return view('Posts.create');

    }

    public function store(PostRequest $request)
    {

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $post = Post::create($data);

        return redirect()->route('posts.home')->with('success','Post Created Successfully');

    }

    public function edit($id)
    {

        $post = Post::FindOrFail($id);
        if (auth()->user()->id !== $post->user_id) {

            return redirect()->route('posts.home')->with('error', 'You are not authorized to edit this post');
        }
        return view('Posts.edit',compact('post'));

    }

    public function update(PostRequest $request, $id)
    {

        $post = Post::FindOrFail($id);
        $data = $request->validated();
        $post->update($data);

        return redirect()->route('posts.home')->with('success','Post Updated Successfully');

    }

    public function delete($id)
    {

        $post = Post::FindOrFail($id);
        if(auth()->user()->id !== $post->user_id){

            return redirect()->route('posts.home')->with('error', 'You are not authorized to delete this post');
        }
        $post->delete();

        return redirect()->route('posts.home')->with('success','Post Deleted Successfully');

    }
}
