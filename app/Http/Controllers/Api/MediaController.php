<?php

namespace App\Http\Controllers\Api;

use App\Media;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\Media as MediaResource;

class MediaController
{
    public function download(Media $media)
    {
        $path = public_path('uploads/' . $media->filename);
        
        return response()->download($path, $media->formattedName());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'paper_id' => 'nullable|exists:papers,id',
            'name'     => 'required|string',
            'media'    => 'required|file'
        ]);

        $file = $request->file('media');

        $data['filename'] = Str::random() . '.' . $file->extension();
        $file->move(public_path('uploads'), $data['filename']);
        
        Media::where('paper_id', $data['paper_id'] ?? null)->delete();
        $media = Media::create($data);

        return new MediaResource($media);
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        $file  = public_path('uploads/' . $media->filename);

        if (file_exists($file)) {
            unlink($file);
        }

        $media->delete();

        return response()->json('No Content', 204);
    }
}