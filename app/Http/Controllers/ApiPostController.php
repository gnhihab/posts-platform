<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Sample Post Title"
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         example="Sample post content goes here..."
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-08-30T00:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-08-30T00:00:00Z"
 *     )
 * )
 */



class ApiPostController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get all posts",
     *     tags={"Posts"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No Posts"
     *     )
     * )
     */

    public function index()
    {
        $posts = Post::all();

        if($posts == null){
            return response()->json([
                'status' => false,
                "message" => 'No Posts'
            ],404);
        }

        return PostResource::collection($posts);

    }

    /**
     * @OA\Get(
     *     path="/api/post/{id}",
     *     summary="Get a single post by ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post Not Found"),
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        $post = Post::find($id);

        if($post == null){
            return response()->json([
                'status' => false,
                "message" => 'Post Not Found'
            ],404);
        }
        return new PostResource($post);
    }

    public function store(Request $request)
    {

        $validateData = Validator::make($request->all(),
        [
            'title'=>'required|string|max:30',
            'content'=>'required|string|max:255',
        ]
        );

        if($validateData->fails()){
            return response()->json([
                'status' => false,
                "errors"=>$validateData->errors()
            ],401);
        }

        $post = Post::create([
            "title"=>$request->title,
            "content"=>$request->content,
            "user_id"=>Auth::id(),
        ]);

        return response()->json([
            'status' => true,
            "message"=>'Post Created Successfully',
            "post" => $post,
        ],201);
    }

    public function update(Request $request , $id)
    {
        $post = Post::find($id);

        if($post == null){
            return response()->json([
                'status' => false,
                "message" => 'Post Not Found'
            ],404);
        }

        if($post->user_id !== Auth::id()){
            return response()->json([
                'status'=> false,
                'message'=>'You Only Can Edit Your Posts'
            ],403);
        }

        $validateData=Validator::make($request->all(),
        [
            'title'=>'sometimes|string|max:30',
            'content'=>'sometimes|string|max:255',
        ]
        );

        if($validateData->fails()){
            return response()->json([
                'status' => false,
                "errors"=>$validateData->errors()
            ],401);
        }

        $post->update([
            "title"=>$request->title,
            "content"=>$request->content,
        ]);

        return response()->json([
            'status' => true,
            "message"=>'Post Updated Successfully',
            "post"=>new PostResource($post)
        ],201);

    }

    public function delete($id)
    {
        $post = Post::find($id);

        if($post == null){
            return response()->json([
                'status' => false,
                "message" => 'Post Not Found'
            ],404);
        }

        if($post->user_id !== Auth::id()){
            return response()->json([
                'status'=>false,
                'message'=>'You Only Can Delete Your Posts'
            ],403);
        }

        $post->delete();

        return response()->json([
            'status' => true,
            "message"=>'Post Deleted Successfully'
        ],201);

    }


}
