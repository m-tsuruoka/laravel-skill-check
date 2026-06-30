<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return BookResource::collection(Book::with('category')->get());
    }

     public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'published_at' => 'nullable|date',
        ]);

        $book = Book::create($validated);

        return (new BookResource($book))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $id)
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return new BookResource($book);
    }


    public function update(Request $request, string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'published_at' => 'nullable|date',
        ]);

        $book->update($validated);

        return new BookResource($book); 
    }


    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $book->delete();

        
        return response()->noContent(); 
    }
}