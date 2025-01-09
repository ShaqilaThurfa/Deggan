<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{


    public function index()
    {
        $news = News::all()->sortByDesc('created_at')->map(function ($item) {
            $item->content = substr($item->content, 0, 100) . '...';
            return $item;
        });

        return response()->json($news);
    }


    public function show($slug)
    {
        $news = News::where('slug', $slug)->first();
        return response()->json($news);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/news', $image->hashName());
            $validatedData['image'] = $image->hashName();
        }

        $validatedData['user_id'] = auth()->id();
        $news = News::create($validatedData);

        return response()->json($news, 201);
    }


    public function update(Request $request, $id)
    {
        $news = News::findOrfail($id);

        $this->authorize('update', $news);

        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image_path = storage_path('app/public/news/' . $news->image);

            
            if ($news->image && file_exists($image_path)) {
                unlink($image_path);
                Storage::delete('public/news/' . $news->image);
            }

            
            $image = $request->file('image');
            $image->storeAs('public/news', $image->hashName());
            $validatedData['image'] = $image->hashName();
        }

        

      
        $news->update([
            'title' => $validatedData['title'] ?? $news->title,
            'content' => $validatedData['content'] ?? $news->content,
            'image' => $validatedData['image'] ?? $news->image,
        ]);

        return response()->json($news);
    }

    public function destroy($id)
    {
        $news = News::find($id);
        $news->delete();
        return response()->json($news);
    }

    public function search($title)
    {
        $news = News::where('title', 'like', '%' . $title . '%')->get();
        return response()->json($news);
    }
}
