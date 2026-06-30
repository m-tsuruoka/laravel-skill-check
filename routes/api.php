<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
/*
|--------------------------------------------------------------------------
| API ルート（ここに課題のルートを追加していきます）
|--------------------------------------------------------------------------
| ここに書いたルートには自動で /api プレフィックスが付きます。
| 例: Route::get('/books', ...) は GET /api/books になります。
|
| ★基礎課題: 書籍一覧・詳細
|   Route::apiResource('books', BookController::class)->only(['index', 'show']);
|
| ★応用課題: CRUD を完成（store/update/destroy を追加）
|   Route::apiResource('books', BookController::class);
|
| ★発展課題: 認証（register/login）と、書き込み系の保護
|   Route::post('/auth/register', [AuthController::class, 'register']);
|   Route::post('/auth/login',    [AuthController::class, 'login']);
|   Route::middleware('auth:sanctum')->group(function () {
|       Route::apiResource('books', BookController::class)->only(['store', 'update', 'destroy']);
|       Route::post('/auth/logout', [AuthController::class, 'logout']);
|   });
|
| 上のコメントは設計の指針です。実際のルート定義は自分で記述してください。
*/

Route::apiResource('books', BookController::class);

// install:api が用意した認証ユーザー取得ルート（Sanctum 動作確認用・そのままでOK）
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
