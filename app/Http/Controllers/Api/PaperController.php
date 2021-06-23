<?php

namespace App\Http\Controllers\Api;

use App\Paper;
use App\Media;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\Media as MediaResource;
use App\Http\Resources\Paper as PaperResource;

class PaperController extends Controller
{
    public function index(Request $request)
    {
        $parent_id = $request->parent_id;
        $parent_id = $parent_id == 'null' ? null : $parent_id;

        $papers = Paper::where('parent_id', $parent_id)->get();

        $media = Media::where('paper_id', $parent_id)->first();

        return [
            'media' => $media ? new MediaResource($media) : null,
            'data'  => PaperResource::collection($papers)
        ];
    }

    public function show(Paper $paper)
    {
        return new PaperResource($paper);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:papers,id'
        ]);

        $paper = Paper::create($data);

        $paper->root = $paper->parent_id == null;
        $paper->leaf = true;
        $paper->save();

        $paper->parent()->update(['leaf' => false]);

        return new PaperResource($paper);
    }

    public function update(Request $request, Paper $paper)
    {
        $paper->update($request->only('name'));

        return response()->json('OK');
    }

    public function destroy(Paper $paper)
    {
        $paper->delete();

        return response()->json('No Content', 204);
    }
}
