<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\BookResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
    return BookResource::collection($books);
    }

    public function show(string $id)
    {
        try {
        // データベースから検索し、なければ ModelNotFoundException を発生させる
        $book = Book::findOrFail($id);
        
        // データがあれば通常通り API Resource で整形して返す (200)
        return new BookResource($book);
        
    } catch (ModelNotFoundException $e) {
        // ❌ データがなかった場合、ここで404レスポンスを直接作って返す
        return response()->json([
            'message' => '404 Not Found' // 👈 返したいシンプルなメッセージ
        ], 404);
    }
    }
}