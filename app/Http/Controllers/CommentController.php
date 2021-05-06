<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\CommentResource|\Illuminate\Http\Response
     */
    public function store(Request $request): CommentResource|\Illuminate\Http\Response
    {
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
     * @return void
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
