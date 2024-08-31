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

        if ($posts == null) {
            return response()->json([
                'status' => false,
                "message" => 'No Posts'
            ], 404);
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

        if ($post == null) {
            return response()->json([
                'status' => false,
                "message" => 'Post Not Found'
            ], 404);
        }
        return new PostResource($post);
    }

    /**
     * @OA\Post(
     *     path="/api/post/create",
     *     summary="Create a new post",
     *     description="Creates a new post with a title and content",
     *     tags={"Posts"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="title", type="string", maxLength=30, example="My Post Title"),
     *             @OA\Property(property="content", type="string", maxLength=255, example="This is the content of my post.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post Created Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post Created Successfully"),
     *             @OA\Property(property="post", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string", example="The title field is required.")),
     *                 @OA\Property(property="content", type="array", @OA\Items(type="string", example="The content field is required."))
     *             )
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {

        $validateData = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:30',
                'content' => 'required|string|max:255',
            ]
        );

        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                "errors" => $validateData->errors()
            ], 401);
        }

        $post = Post::create([
            "title" => $request->title,
            "content" => $request->content,
            "user_id" => Auth::id(),
        ]);

        return response()->json([
            'status' => true,
            "message" => 'Post Created Successfully',
            "post" => $post,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/post/update/{id}",
     *     summary="Update your post",
     *     description="Allows an authenticated user to update their own post",
     *     tags={"Posts"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="title", type="string", maxLength=30, example="Update post title"),
     *             @OA\Property(property="content", type="string", maxLength=255, example="Update post content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post Updated Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post Updated Successfully"),
     *             @OA\Property(property="post", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You Only Can Edit Your Posts")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post Not Found")
     *         )
     *     )
     * )
     */


    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if ($post == null) {
            return response()->json([
                'status' => false,
                "message" => 'Post Not Found'
            ], 404);
        }

        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'You Only Can Edit Your Posts'
            ], 403);
        }

        $validateData = Validator::make(
            $request->all(),
            [
                'title' => 'sometimes|string|max:30',
                'content' => 'sometimes|string|max:255',
            ]
        );

        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                "errors" => $validateData->errors()
            ], 401);
        }

        $post->update([
            "title" => $request->title,
            "content" => $request->content,
        ]);

        return response()->json([
            'status' => true,
            "message" => 'Post Updated Successfully',
            "post" => new PostResource($post)
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/post/delete/{id}",
     *     summary="Delete your post",
     *     description="Allows an authenticated user to delete their own post",
     *     tags={"Posts"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post Deleted Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post Deleted Successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You Only Can Edit Your Posts")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post Not Found")
     *         )
     *     )
     * )
     */

    public function delete($id)
    {
        $post = Post::find($id);

        if ($post == null) {
            return response()->json([
                'status' => false,
                "message" => 'Post Not Found'
            ], 404);
        }

        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'You Only Can Delete Your Posts'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'status' => true,
            "message" => 'Post Deleted Successfully'
        ], 201);
    }
}
