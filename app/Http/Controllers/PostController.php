<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
	public function index()
	{
		$post = Post::latest()->with('user:id,name,image')->withCount('comments', 'likes')
			->with('likes', function ($like) {
				return $like->where('user_id', auth()->id())
					->select('id', 'user_id', 'post_id')
					->get();
			})
			->get();

		return response([
			'posts' => $post,
		], 200);
	}

	public function show($id)
	{
		$post = Post::where('id', $id)->withCount('comments', 'likes')->get();

		return response([
			'post' => $post,
		], 200);
	}

	public function store(Request $request)
	{
		$attrs = $request->validate([
			'body' => 'required|string',
		]);

		$attrs['user_id'] = auth()->id();

		// $attrs['image'] = $this->saveImage($request->image, 'posts');

		if ($request->hasFile('image')) {
			$imagePath = $request->file('image')->store('posts', 'public');
			$attrs['image'] = $imagePath;
		}

		// if ($request->hasFile('file') && $request->file('file')->isValid()) {
		// 	$path = $request->file->store('users-avatar', 'public');
		// 	return response()->json(['path' => $path], 200);
		// }

		$post = Post::create($attrs);

		return response([
			'message' => 'Post created',
			'post' => $post,
		], 200);
	}

	public function update(Request $request, $id)
	{

		//find the post
		$post = Post::find($id);

		if (!$post) {
			return response([
				'message' => 'Post not found',
			], 403);
		}

		//check if post belong to user
		if ($post->user_id != auth()->id()) {
			return response([
				'message' => 'Permission denied',
			], 403);
		}

		$attrs = $request->validate([
			'body' => 'required|string',
		]);

		// $attrs['user_id'] = auth()->id();

		$post->update($attrs);

		return response([
			'message' => 'Post updated',
			'post' => $post,
		], 200);
	}

	//delete
	public function destroy($id)
	{

		$post = Post::find($id);

		if (!$post) {
			return response([
				'message' => 'Post not found.',
			], 403);
		}

		if ($post->user_id != auth()->user()->id) {
			return response([
				'message' => 'Permission denied.',
			], 403);
		}

		// $post->comments()->delete();
		// $post->likes()->delete();
		$post->delete();

		return response([
			'message' => 'Post deleted.',
		], 200);
	}

}
