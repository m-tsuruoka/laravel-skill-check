# Laravel スキルチェック課題 📚 書籍 API

**Laravel 13**（`laravel/framework` v13.x）で **書籍管理 REST API** を実装するスキルチェックです。「はじめてのLaravel」で学んだ範囲
（ルーティング・Controller・Eloquent・マイグレーション・リレーション・バリデーション・API Resource・Sanctum 認証・PHPUnit）で解けます。

- **想定時間**: 約 3〜4 時間（半日）
- **採点**: 各課題末尾の「✅ 自己採点チェックリスト」＋ curl / Postman での動作確認
- **進め方**: 課題ごとにブランチ → 実装 → Pull Request（説明に Postman 実行結果や curl 結果のスクショを添付）

---

## 1. 環境構築（最小手順）

PHP 8.3 以上（Laravel 13 の要件）と Composer が必要です。DB は **SQLite**（サーバ不要・設定済み）。
※ PHP トラック（php-skill-check）と同じ PHP 8.3 系で動作確認しています。

```bash
composer install
cp .env.example .env          # すでに .env がある場合は不要
php artisan key:generate
php artisan migrate --seed    # books 16件 / categories 4件 を投入
php artisan serve             # http://localhost:8000
```

確認: `php artisan tinker` で `App\Models\Book::with('category')->first()` が書籍＋カテゴリを返せば準備完了。

### 用意済みのもの（スターター）
- `app/Models/Book.php` / `Category.php`（`$fillable`・リレーション定義済み）
- マイグレーション（categories → books、外部キーつき）
- `BookFactory` / `DatabaseSeeder`（カテゴリ4・書籍16件）
- `php artisan install:api` 実行済み（`routes/api.php`・Sanctum 導入済み、User に `HasApiTokens` 追加済み）

### あなたが実装するもの
- ルート（`routes/api.php`）・Controller（`app/Http/Controllers/`）・API Resource・FormRequest・テスト・認証用 Controller

> `routes/api.php` に書いたルートには自動で `/api` プレフィックスが付きます（`/books` → `/api/books`）。

---

## 2. 基礎課題 ⭐ — 書籍一覧・詳細 API

**要件**
- `GET /api/books`：書籍を一覧で返す（JSON）
- `GET /api/books/{id}`：1 件を返す。存在しない ID は **404**
- **API Resource**（`php artisan make:resource BookResource`）でレスポンスを整形する（必要な項目だけ返す）

**ヒント**
- `php artisan make:controller BookController --api` で雛形（index/show/store/update/destroy）が生成される
- `routes/api.php`: `Route::apiResource('books', BookController::class)->only(['index', 'show']);`
- 一覧は `Book::all()` または `Book::with('category')->get()` → `BookResource::collection(...)`
- ルートモデルバインディング（`show(Book $book)`）を使うと存在しない ID は自動 404

**✅ 自己採点チェックリスト**
- [ ] `GET /api/books` が 200 で書籍配列を返す
- [ ] `GET /api/books/1` が 200 で 1 件返す
- [ ] `GET /api/books/99999` が **404** を返す
- [ ] レスポンスが API Resource で整形されている

**動作確認**
```bash
curl http://localhost:8000/api/books -H "Accept: application/json"
curl http://localhost:8000/api/books/1 -H "Accept: application/json"
curl -i http://localhost:8000/api/books/99999 -H "Accept: application/json"   # 404
```

---

## 3. 応用課題 ⭐⭐ — CRUD 完成 ＋ バリデーション ＋ リレーション

**要件**
- `POST /api/books`：作成（成功 **201**）
- `PUT/PATCH /api/books/{id}`：更新
- `DELETE /api/books/{id}`：削除（**204**）
- **バリデーション**（`title` 必須・最大255、`author` 必須、`category_id` は `exists:categories,id`、`price` は整数 0 以上）。失敗時は **422**＋エラー内容
- 一覧・詳細のレスポンスに **カテゴリ名** を含める（`book.category` のリレーションを使う）

**ヒント**
- バリデーションは `php artisan make:request StoreBookRequest`（FormRequest）か、Controller 内の `$request->validate([...])`
- FormRequest を使う場合は `authorize()` の戻り値に注意（`return true;` に）
- API Resource で `'category' => $this->category->name` のようにリレーションを含める（N+1 を避けるなら `with('category')`）

**✅ 自己採点チェックリスト**
- [ ] POST で作成でき 201 が返る
- [ ] 不正な入力（必須欠落・存在しない category_id 等）で **422**＋エラーメッセージ
- [ ] PUT/PATCH で更新、DELETE で 204
- [ ] 一覧・詳細にカテゴリ名が含まれる

**動作確認（例）**
```bash
# 作成（201）
curl -i -X POST http://localhost:8000/api/books \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"title":"新しい本","author":"著者名","category_id":1,"price":2500}'
# バリデーションエラー（422）
curl -i -X POST http://localhost:8000/api/books \
  -H "Accept: application/json" -H "Content-Type: application/json" -d '{}'
```

---

## 4. 発展課題 ⭐⭐⭐ — Sanctum 認証 ＋ PHPUnit テスト

**要件**
- 認証 API：`POST /api/auth/register`・`POST /api/auth/login`（トークンを発行して返す）
- **書き込み系（store/update/destroy）を `auth:sanctum` で保護**（未認証は 401）。一覧・詳細は公開のままでよい
- **PHPUnit テスト**を数本書く（`php artisan test`）:
  - 一覧が 200 を返す / 詳細の存在しない ID が 404
  - バリデーション失敗で 422
  - 未認証で作成すると 401、認証済み（`Sanctum::actingAs` か `actingAs`）で 201

**ヒント**
- `$user->createToken('api')->plainTextToken` でトークン発行（User には `HasApiTokens` 追加済み）
- ログイン照合は `Hash::check()`（パスワードは User の `hashed` キャストで自動ハッシュ）
- テストは `php artisan make:test BookApiTest`、`use RefreshDatabase;`、`$this->getJson()/postJson()`

**✅ 自己採点チェックリスト**
- [ ] register / login でトークンが返る
- [ ] 未認証で `POST /api/books` が 401
- [ ] Bearer トークンつきで作成できる
- [ ] `php artisan test` が全て pass する

---

## 5. 追加課題（早く終わった人向け）

- **A. 検索 / 絞り込み**: `GET /api/books?category_id=1` や `?keyword=...`（title 部分一致）
- **B. ページネーション**: `Book::paginate(10)`（レスポンス形式が変わる点に注意）
- **C. 画像アップロード**: 表紙画像を `store()` で受け取り `storage` に保存（`php artisan storage:link`）
- **D. 通知メール**: 書籍登録時に管理者へ Mail / Notification を送る（`MAIL_MAILER=log` で確認）

---

## 6. 提出方法
1. 課題ごとにブランチ（例: `git switch -c kadai/basic`）
2. 実装 → コミット → Pull Request
3. PR 説明に **Postman または curl の実行結果** と **自己採点チェックリストの結果** を貼る
4. 余裕があれば Postman コレクションを `postman/` にエクスポート（v2.1 形式）

---

## 7. 出題範囲について
「はじめてのLaravel」で学んだ範囲（ルーティング／Controller／Eloquent・リレーション／マイグレーション／バリデーション／API Resource・HTTPステータス／Sanctum 認証／PHPUnit）で解けます。
キュー・ブロードキャスト等の発展機能は使いません（追加課題のアップロード/メールは学習済み範囲）。
