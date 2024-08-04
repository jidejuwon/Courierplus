<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Helpers\blogHelper;

class BlogController extends Controller
{
    // Fetch all blogs
    public function index()
    {
        $blogs = Blog::all();
        return blogHelper::successResponse($blogs);
    }

    // Create a new blog
    public function store(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validator->fails())
            return blogHelper::errorResponse($validator->errors()->first());

        if ($request->hasFile('image')) {
            $upload_image = blogHelper::uploadFile($request->file('image'));

            if(!$upload_image['status'])
                return blogHelper::errorResponse("Image upload fail", '', 400);

            $imagePath = $upload_image['url'];
            $image_public_id = $upload_image['public_id'];
        }

        $blog = Blog::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'image_url' => $imagePath ?? null,
            'image_public_id' => $image_public_id ?? null
        ]);

        return blogHelper::successResponse($blog,null,201);
    }

    // Fetch details of a specific blog
    public function show($id)
    {
        $blog = Blog::where('id', $id)->with('posts')->get();
        return blogHelper::successResponse($blog);
    }

    // Update an existing blog
    public function update(Request $request, $id)
    {
        $validator =  Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validator->fails())
            return blogHelper::errorResponse($validator->errors()->first());

        $blog = Blog::findOrFail($id);

        if($request->hasFile('image')){

            $upload_image = blogHelper::uploadFile($request->file('image'));

            if(!$upload_image['status'])
                return blogHelper::errorResponse("Image upload fail", '', 400);

            // delete previous image
            blogHelper::deleteFile($blog->image_public_id);

            $blog->update([
                'image_url' =>  $upload_image['url'],
                'image_public_id' => $upload_image['public_id']
            ]);

        }

        $blog->update(['title' => $request->title,'content' => $request->content]);
        return blogHelper::successResponse($blog);
    }

    // Delete a blog
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        // clear media from storage
        if($blog->image_public_id)
            blogHelper::deleteFile($blog->image_public_id);
        $blog->delete();
        return blogHelper::successResponse('',null);
    }
}
