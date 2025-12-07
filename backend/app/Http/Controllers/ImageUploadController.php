<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class ImageUploadController extends Controller
{
    
    public function store(Request $request){
        $request->validate([
            'image' => 'required|image|max:2048', // max 2MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public'); // stores in storage/app/public/images
            return response()->json([
                'message' => 'Image uploaded successfully',
                'path' => $path,
                'url' => asset("storage/$path")  // public URL
            ]);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }   

    public function storeMultiple(Request $request)
    {
        
        Log::info('Request all:', $request->all());
        // Log::info('Request files:', $request->file('images'));
        $request->validate([
            'images.*' => 'required|image|max:2048', // validate each image
        ]);

        $uploaded = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $uploaded[] = [
                    'path' => $path,
                    'url' => asset("storage/$path"),
                ];
            }

            return response()->json([
                'message' => 'Images uploaded successfully',
                'files' => $uploaded // <-- return uploaded files info
            ]);
        }

        return response()->json(['message' => ' '], 400);
    }

}
