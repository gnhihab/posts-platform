<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Mail\CommentAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


/**
 * @OA\Schema(
 *     schema="Comment",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         example="Sample comment content goes here..."
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="post_id",
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


class ApiCommentController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/comment/create/{post}",
     *     summary="Add comment",
     *     description="Creates a new comment to posts",
     *     tags={"Comments"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", maxLength=255, example="Comment content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment Created Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Comment Created Successfully"),
     *             @OA\Property(property="comment", ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="content", type="array", @OA\Items(type="string", example="The content field is required."))
     *             )
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Delete(
     *     path="/api/comment/delete/{id}",
     *     summary="Delete your comment",
     *     description="Allows an authenticated user to delete their own post",
     *     tags={"Comments"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment Deleted Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Comment Deleted Successfully")
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
     *         description="Comment not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Comment ID Not Found")
     *         )
     *     )
     * )
     */

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
