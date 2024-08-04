<?php

namespace App\Helpers;


use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Audit;


class blogHelper{

    public static function cloudinary(){
        return new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
    }

    public static function uploadFile($file){
        $cloudinary = self::cloudinary();

        try {
            // Upload the file to Cloudinary
            $uploadResponse = $cloudinary->uploadApi()->upload($file->getPathname());

            // Get the URL of the uploaded file
            $imageUrl = $uploadResponse['secure_url'];
            $public_id =  $uploadResponse['public_id'];

            // Return the image URL in the response
            return ['status' => true, 'url' => $imageUrl, 'public_id' => $public_id];

        } catch (\Exception $e) {

            // Log::error('Cloudinary upload failed: ' . $e->getMessage());
            Log::error('Cloudinary upload failed: '.$e->getMessage(), [
                'file' => $file->getPathname(),
                'cloudinary_response' => $e->getTraceAsString()
            ]);

            return ['status' => false, 'error' => 'Failed to upload image.'];
        }

    }

    public static function deleteFile($public_id){
        try {

            $cloudinary = self::cloudinary();
            $cloudinary->uploadApi()->destroy($public_id);
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage(), [
                'cloudinary_response' => $e->getTraceAsString()
            ]);
        }
    }

    public static function successResponse($data = '', $message = null, $status = 200){
        return response()->json(['error' => false, 'message' => $message ?? 'Operation successful', 'data' => $data], $status);
    }

    public static function errorResponse($message = null, $data = '', $status = 400){
        return response()->json(['error' => true, 'message' => $message ?? 'Operation failed', 'data' => $data], $status);
    }

}
