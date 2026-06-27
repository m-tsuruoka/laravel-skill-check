# Postman コレクション

発展課題まで進めたら、作成した API の Postman コレクションをこのフォルダに
エクスポート（Collection v2.1 形式）して提出してください。

- リクエスト例: 一覧 / 詳細 / 作成 / 更新 / 削除 / register / login
- 認証が必要なリクエストには `Authorization: Bearer {{token}}` を設定
- 環境変数 `base_url`（`http://localhost:8000`）と `token` を使うと整理しやすいです

curl での確認で代用しても構いません（その場合は実行結果を PR に貼ってください）。
