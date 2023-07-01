<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
	public function index($id)
	{

		//find the post
		$post = Post::find($id);

		if (!$post) {
			return response([
				'message' => 'Post not found',
			], 403);
		}

		return response([
			'comments' => $post->comments()->with('user:id,name,image')->get(),
		], 200);
	}

	//create a comment
	public function store(Request $request, $id)
	{
		//find the post
		$post = Post::find($id);

		if (!$post) {
			return response([
				'message' => 'Post not found',
			], 403);
		}

		$attrs = $request->validate([
			'comment' => 'required|string',
		]);

		$attrs['user_id'] = auth()->id();
		$attrs['post_id'] = $id;

		//store comment
		Comment::create($attrs);

		return response([
			'message' => 'Comment created',
		], 200);
	}

	public function update(Request $request, $id)
	{

		//find the post
		$comment = Comment::find($id);

		if (!$comment) {
			return response([
				'message' => 'Comment not found',
			], 403);
		}

		//check if post belong to user
		if ($comment->user_id != auth()->id()) {
			return response([
				'message' => 'Permission denied',
			], 403);
		}

		$attrs = $request->validate([
			'comment' => 'required|string',
		]);

		// $attrs['user_id'] = auth()->id();

		$comment->update($attrs);

		return response([
			'message' => 'Comment updated',
			// 'comment' => $comment,
		], 200);
	}

	//delete
	public function destroy($id)
	{
		//find the post
		$comment = Comment::find($id);

		if (!$comment) {
			return response([
				'message' => 'Comment not found',
			], 403);
		}

		//check if post belong to user
		if ($comment->user_id != auth()->id()) {
			return response([
				'message' => 'Permission denied',
			], 403);
		}

		$comment->delete();

		return response([
			'message' => 'Comment deleted',
		], 200);

	}

}
