<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageProxyController extends Controller
{
    /**
     * Serve a file from the `public` storage disk (storage/app/public).
     * This acts as a fallback when the `public/storage` symlink is missing.
     */
    public function show($path)
    {
        $disk = Storage::disk('public');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);

        $mime = @mime_content_type($fullPath) ?: 'application/octet-stream';

        return response()->file($fullPath, ['Content-Type' => $mime]);
    }
}
