<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\blogHelper;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $postId){

        $data = array_merge($request->all(), ['post_id' => $postId]);

        // Validate the request data
        $validator = Validator::make($data, [
            'post_id' => 'required|integer|exists:posts,id',
            'comment' => 'required|string|max:255',
        ]);

        if ($validator->fails())
            return blogHelper::successResponse('Invalid comment ');

        Comment::updateOrCreate([
            'user_id' => auth()->id(),
            'post_id' => $postId,
            'comment' => $request->comment,
        ]);

        return blogHelper::successResponse();
    }
}
