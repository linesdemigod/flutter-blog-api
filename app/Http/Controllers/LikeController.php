<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
	public function likeOrUnlike($id)
	{
		//find the post
		$post = Post::find($id);

		if (!$post) {
			return response([
				'message' => 'Post not found',
			], 403);
		}

		$like = $post->likes()->where('user_id', auth()->id())->first();

		//check if not liked then like
		if (!$like) {
			Like::create([
				'post_id' => $id,
				'user_id' => auth()->id(),
			]);
		}

		return response([
			'message' => 'Liked',
		], 200);

		$like->delete();

		return response([
			'message' => 'Disliked',
		], 200);
	}
}
