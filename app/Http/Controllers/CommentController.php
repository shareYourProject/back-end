<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    /**
     * Instantiate a new PostController instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get a comment resource
     *
     * @param Request $request
     * @param Comment $comment
     * @return CommentResource
     */
    public function get(Request $request, Comment $comment): CommentResource
    {
        if ($request->user()->cannot('view', $comment)) {
            abort(403);
        }

        return new CommentResource($comment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\CommentResource|\Illuminate\Http\Response
     */
    public function store(Request $request): CommentResource|\Illuminate\Http\Response
    {
        if ($request->user()->cannot('create', Comment::class)) {
            abort(403);
        }

        $validatedData = $request->validate([
            'content' => 'required|max:255',
            'post_id' => 'required|numeric|exists:posts,id',
        ]);

        $comment = new Comment;
        $comment->content = $validatedData['content'];
        $comment->author()->associate(Auth::user());

        Post::find($validatedData['post_id'])->comments()->save($comment);

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return CommentResource
     */
    public function update(Request $request, Comment $comment): CommentResource
    {
        if ($request->user()->cannot('update', $comment)) {
            abort(403);
        }

        $validatedData = $request->validate([
            'content' => 'required|max:255'
        ]);

        $comment->content = $validatedData['content'];
        $comment->save();

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \App\Models\Comment $comment
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        if ($request->user()->cannot('delete', $comment)) {
            abort(403);
        }

        $comment->delete();

        return new JsonResponse();
    }
}
