## 概要
COACHTECH 教材 Tutorial 10-6「Webセキュリティ ハンズオン」で作成した問い合わせフォームです。入力内容を検証して送信完了画面に表示し、CSRF と XSS への対策を実装しています。

## 使用技術
- PHP 8.1以上
- Laravel 10.x
- Laravel Sail / Docker Compose
- Blade
- CSRF 保護（`@csrf`、CSRF ミドルウェア）
- XSS 対策（Blade の自動エスケープ、HTML Purifier）
- PHPUnit Feature Test

## 学んだこと
- `@csrf` はフォームにトークンを出力し、CSRF ミドルウェアがリクエスト時に検証する。対象 URI を除外設定に入れると検証されない。
- Blade の `{{ }}` は値を HTML エスケープする。`{!! !!}` はそのまま出力するため、ユーザー入力には使わない。
- HTML を許可する場合はサーバー側でサニタイズする。プレーンテキストなら Blade のエスケープ出力で表示できる。

## 今回つまずいたところ
- `{!! !!}` から `{{ }}` に直すとき、波括弧を左右それぞれ2つにする必要があるのをしていなかった。
- Purifier は Blade ビューに未定義の変数を追加するのではなく、コントローラーで入力値を処理すること。コントローラーから渡していない `$post` をビューで参照して送信時にエラーになった。

## 動作確認
Docker が利用できる環境でプロジェクトのルートから実行します。

```sh
./vendor/bin/sail up -d
```

ブラウザーで [http://localhost/contact](http://localhost/contact) を開き、名前・メールアドレス・メッセージを入力して送信します。送信後に完了画面が表示されます。名前欄に `<script>alert(1)</script>` を入力しても、スクリプトとして実行されず文字列として表示されることを確認できます。

Feature テストは次のコマンドで実行します。

```sh
./vendor/bin/sail artisan test --filter=ContactTest
```

Laravel のテスト環境では CSRF ミドルウェアの検証が通常スキップされるため、この Feature テストだけでは CSRF のトークン検証自体は確認できません。ブラウザーでフォームを送信し、CSRF トークンを含む通常の送信が成功することも確認してください。
