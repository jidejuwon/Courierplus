<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helpers\blogHelper;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    public function store(Request $request, $postId){

        $validator = Validator::make(['post_id' => $postId],[
            'post_id' => 'required|integer|exists:posts,id',
        ]);

        if ($validator->fails())
            return blogHelper::successResponse('Invalid post ID');

        Like::updateOrCreate([
            'user_id' => auth()->id(),
            'post_id' => $postId
        ]);

        return blogHelper::successResponse();
    }
}
