<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        $file = $request->file('filename');

        // Store the file in the public folder
        $filePath = $file->storeAs('public', $file->getClientOriginalName());

        // Return the path of the uploaded file
        return response()->json(['path' => $filePath]);
    }
}
?>