<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Helpers\blogHelper;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Blog;

class PostController extends Controller
{
    // Fetch all posts under a specific blog
    public function index($blogId)
    {
        $posts = Post::where('blog_id', $blogId)->get();
        return blogHelper::successResponse($posts);
    }

    // Create a new post under a specific blog
    public function store(Request $request, $blogId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validator->fails())
            return blogHelper::errorResponse($validator->errors()->first());

        if(!Blog::where('id', $blogId)->exists())
            return blogHelper::errorResponse("Invalid blog ID",'',404);

        if($request->hasFile('image')){
            $upload_image = blogHelper::uploadFile($request->file('image'));

            if(!$upload_image['status'])
                return blogHelper::errorResponse("Image upload fail", '', 400);

            $imagePath = $upload_image['url'];
            $image_public_id = $upload_image['public_id'];
        }

        $post = Post::create([
            'blog_id' => $blogId,
            'title' => $request->title,
            'content' => $request->content,
            'image_url' => $imagePath ?? null,
            'image_public_id' => $image_public_id ?? null
        ]);

        return blogHelper::successResponse($post,null,201);
    }

    // Fetch details of a specific post and its likes and comments
    public function show($blogId, $postId)
    {
        $post = Post::with(['likes', 'comments'])->where('blog_id', $blogId)->findOrFail($postId);
        return blogHelper::successResponse($post);
    }

    // Update an existing post
    public function update(Request $request, $blogId, $postId)
    {
        $validator =  Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validator->fails())
            return blogHelper::errorResponse($validator->errors()->first());

        if(!Blog::where('id', $blogId)->exists())
            return blogHelper::errorResponse("Invalid blog ID",'',404);

        if(!Post::where('id', $postId)->exists())
            return blogHelper::errorResponse('Invalid post ID',null,404);

        $post = Post::findOrFail($postId);

        if($request->hasFile('image')){
            $upload_image = blogHelper::uploadFile($request->file('image'));

            if(!$upload_image['status'])
                return blogHelper::errorResponse("Image upload fail", '', 400);

            // delete previous image
            blogHelper::deleteFile($post->image_public_id);

            $post->update([
                'image_url' =>  $upload_image['url'],
                'image_public_id' => $upload_image['public_id']
            ]);
        }

        $post->update(['title' => $request->title,'content' => $request->content]);
        return blogHelper::successResponse($post);
    }

    // Delete a post
    public function destroy($blogId, $postId)
    {
        $post = Post::where('blog_id', $blogId)->findOrFail($postId);
        // clear media from storage
        if($post->image_public_id)
            blogHelper::deleteFile($post->image_public_id);

        $post->delete();
        return blogHelper::successResponse();
    }
}
