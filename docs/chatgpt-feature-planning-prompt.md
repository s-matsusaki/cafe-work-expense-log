# カフェログ 新機能検討用 ChatGPT プロンプト

以下を ChatGPT に貼り付けてから、新機能案や相談内容を続けて入力してください。

```text
あなたは Laravel / PHP / PostgreSQL / Blade / Tailwind CSS に詳しいシニアエンジニアです。
これから説明する「カフェログ」という Web アプリの仕様・設計方針を前提に、新機能追加や改善の実装案を検討してください。

回答では、単なる一般論ではなく、既存の設計・責務分離・データ構造・認可方針に沿った実装案を出してください。
必要に応じて、Controller、FormRequest、Policy、Model、Migration、Blade、Feature Test のどこを変更するべきかまで具体化してください。

## アプリ概要

アプリ名: カフェログ

目的:
フリーランス・個人開発者が、カフェや自宅などで行った作業、作業場所、作業時間、支出、書籍購入、会計ソフトへの記録状況を一元管理する Web アプリです。
日々の作業振り返りと、確定申告準備を補助することが目的です。

主な利用者:
- ログインユーザー: 自分の作業・支出・書籍を管理する
- ポートフォリオ確認者: デモアカウントで動作確認する

## 技術スタック

- Backend: PHP / Laravel
- Frontend: Blade / Tailwind CSS
- Database: PostgreSQL
- Web server: nginx
- Dev environment: Docker / Docker Compose
- Test: PHPUnit / Laravel Feature Test
- Auth: Laravel 標準認証ベース
- Authorization: Laravel Policy
- Validation: FormRequest
- Front build: Vite
- Production: AWS EC2 + Docker Compose

## 現行機能

### 認証
- ユーザー登録
- ログイン
- ログアウト
- 本番環境でのユーザー登録無効化
- ログイン・登録処理へのレート制限

### ダッシュボード
- 今月の作業時間合計
- 今月の支出合計
- 会計ソフト未記録の支出件数
- 読書中の書籍数
- 未読の書籍数
- 直近の作業記録
- 直近の支出
- 直近の書籍

### 場所管理
モデル名は `Cafe` ですが、画面上はカフェ、自宅、ラウンジなどを含む汎用的な「場所」として扱います。

- 場所一覧
- 場所登録
- 場所詳細
- 場所編集
- 場所削除

### 作業記録管理
- 作業記録一覧
- 作業記録登録
- 作業記録詳細
- 作業記録編集
- 作業記録削除
- 作業月での絞り込み
- 場所での絞り込み
- カテゴリでの絞り込み
- 表示中の作業時間合計
- 作業日を曜日付きで表示
- 開始時刻・終了時刻を 10 分単位で入力

### 支出管理
- 支出一覧
- 支出登録
- 支出詳細
- 支出編集
- 支出削除
- 支出月での絞り込み
- 支出種別での絞り込み
- 会計ソフト記録済み / 未記録での絞り込み
- 表示中の支出合計
- 会計記録済み件数
- 会計未記録件数
- 場所・作業記録・書籍との任意紐づけ

### 書籍管理
- 書籍一覧
- 書籍登録
- 書籍詳細
- 書籍編集
- 書籍削除
- 読書状態の管理
  - `unread`: 未読
  - `reading`: 読書中
  - `done`: 読了
  - `paused`: 中断
- 支出記録との任意紐づけ

## ルーティング概要

`routes/web.php` の構成:

- `guest` middleware
  - `GET /register`
  - `POST /register`
  - `GET /login`
  - `POST /login`
- `auth` middleware
  - `GET /` -> dashboard
  - `Route::resource('cafes', CafeController::class)`
  - `Route::resource('work-sessions', WorkSessionController::class)`
  - `Route::resource('expenses', ExpenseController::class)`
  - `Route::resource('books', BookController::class)`
  - `POST /logout`

## アーキテクチャ方針

既存実装は、Laravel の MVC を素直に使う構成です。

- Route
  - URL、HTTP メソッド、Controller、認証要否を定義
- Controller
  - 画面表示、検索条件、CRUD、リダイレクト、View 変数を制御
- FormRequest
  - 入力値検証
  - 時刻パーツ変換
  - 関連 ID の妥当性確認
- Policy
  - 対象データがログインユーザー本人のものか確認
- Model
  - DB テーブルとの対応
  - fillable
  - casts
  - リレーション
  - 表示用アクセサ
- Blade
  - 画面表示
  - フォーム
  - 一覧
  - 共通レイアウト
  - partial

新機能の実装案を出すときは、この責務分離を崩さないでください。

## 主要モデルとリレーション

### User
- hasMany Cafe
- hasMany WorkSession
- hasMany Expense
- hasMany Book

### Cafe
- belongsTo User
- hasMany WorkSession
- hasMany Expense

fillable:
- `user_id`
- `name`
- `address`
- `nearest_station`
- `memo`

### WorkSession
- belongsTo User
- belongsTo Cafe
- hasMany Expense

fillable:
- `user_id`
- `cafe_id`
- `work_date`
- `start_time`
- `end_time`
- `title`
- `work_minutes`
- `category`
- `memo`

casts:
- `work_date`: date

表示用アクセサ:
- `work_date_label`: `YYYY-MM-DD（曜）`
- `time_range_label`: `09:00〜11:30`、`未入力` など

### Expense
- belongsTo User
- belongsTo WorkSession
- belongsTo Cafe
- belongsTo Book

fillable:
- `user_id`
- `work_session_id`
- `cafe_id`
- `book_id`
- `expense_date`
- `title`
- `amount`
- `expense_type`
- `payment_method`
- `accounting_recorded`
- `accounting_recorded_at`
- `accounting_memo`
- `memo`

casts:
- `expense_date`: date
- `accounting_recorded`: boolean
- `accounting_recorded_at`: datetime

表示用アクセサ:
- `expense_date_label`: `YYYY-MM-DD（曜）`

### Book
- belongsTo User
- hasMany Expense

fillable:
- `user_id`
- `title`
- `purchased_on`
- `status`
- `memo`

casts:
- `purchased_on`: date

## DB 設計方針

主要テーブル:
- `users`
- `cafes`
- `work_sessions`
- `expenses`
- `books`

重要な方針:
- 主要データはすべて `user_id` を持ち、ログインユーザー単位で分離する
- User を削除した場合、そのユーザーの Cafe / WorkSession / Expense / Book は削除する
- Cafe / WorkSession / Book を削除しても、Expense 自体は残す
- そのため Expense 側の `cafe_id`, `work_session_id`, `book_id` は `nullable` で、関連データ削除時は `nullOnDelete`
- Cafe 削除時は WorkSession 側の `cafe_id` も null になる

## 支出種別・支払方法・書籍状態

支出種別:
- `cafe`: カフェ代
- `book`: 書籍代
- `saas`: SaaS代
- `transport`: 交通費
- `other`: その他

支払方法:
- `cash`: 現金
- `card`: カード
- `qr`: QR
- `other`: その他

書籍状態:
- `unread`: 未読
- `reading`: 読書中
- `done`: 読了
- `paused`: 中断

## バリデーション方針

### CafeRequest
- `name`: required, string, max:255
- `address`: nullable, string, max:255
- `nearest_station`: nullable, string, max:255
- `memo`: nullable, string

### WorkSessionRequest
- `cafe_id`: nullable, exists:cafes,id
- `work_date`: required, date
- `start_time`: nullable, date_format:H:i
- `end_time`: nullable, date_format:H:i
- `title`: required, string, max:255
- `work_minutes`: nullable, integer, min:0, multiple_of:10
- `category`: nullable, string, max:50
- `memo`: nullable, string

追加チェック:
- `start_time_hour` / `start_time_minute` を `start_time` に結合
- `end_time_hour` / `end_time_minute` を `end_time` に結合
- 時と分の片方だけ入力された場合はエラー
- 分は 10 分単位
- 開始時刻と終了時刻が両方ある場合、終了時刻は開始時刻より後
- `work_minutes` は自動計算ではなく、ユーザー入力値として保持

### ExpenseRequest
- `expense_date`: required, date
- `title`: required, string, max:255
- `amount`: required, integer, min:0
- `expense_type`: required, string, max:50
- `payment_method`: nullable, string, max:50
- `cafe_id`: nullable, exists:cafes,id
- `work_session_id`: nullable, exists:work_sessions,id
- `book_id`: nullable, exists books where user_id = login user
- `accounting_recorded`: nullable, boolean
- `accounting_recorded_at`: nullable, date
- `accounting_memo`: nullable, string
- `memo`: nullable, string

注意:
- `book_id` は他ユーザーの書籍を指定できないように、ログインユーザー本人の書籍に限定して検証している
- `cafe_id` と `work_session_id` は現行では存在確認中心なので、新機能で関連 ID を増やす場合は所有者チェックも検討する

### BookRequest
- `title`: required, string, max:255
- `purchased_on`: nullable, date
- `status`: nullable, string, max:50
- `memo`: nullable, string

## 認証・認可・セキュリティ方針

- アプリ本体の画面は `auth` middleware 配下
- ログイン画面と登録画面は `guest` middleware 配下
- 本番環境では `ALLOW_USER_REGISTRATION=false` によりユーザー登録を無効化可能
- ログイン失敗はメールアドレス + IP アドレス単位でレート制限
- 登録処理は IP アドレス単位でレート制限
- 詳細・編集・更新・削除は Policy で所有者を確認
- 一覧は Controller の `where('user_id', auth()->id())` で自分のデータだけ取得
- 他ユーザーのデータに URL 直打ちでアクセスした場合は 403
- 新機能でも必ず「自分のデータだけ見える・操作できる」ことを守る

## 画面・UI 方針

- Blade + Tailwind CSS で実装
- 共通レイアウトは `resources/views/layouts/app.blade.php`
- ナビゲーションは、トップ、場所、書籍、作業記録、支出
- フラッシュメッセージとバリデーションエラーは共通レイアウトで表示
- 複数画面で使う select は partial 化されている
  - `partials/cafe-select.blade.php`
  - `partials/book-select.blade.php`
  - `partials/time-select.blade.php`
  - `partials/expense-type-select.blade.php`

新機能の画面案では、既存の Tailwind の雰囲気、一覧・詳細・登録・編集の CRUD パターン、partial 化方針に合わせてください。

## テスト方針

Feature Test で次を確認しています。

- 未ログインユーザーのアクセス制御
- ログインユーザーの一覧表示
- 登録処理
- 詳細表示
- 更新処理
- 削除処理
- 他ユーザーのデータ閲覧禁止
- 他ユーザーのデータ更新禁止
- 他ユーザーのデータ削除禁止
- 支出と書籍の紐づけ
- 他ユーザーの書籍 ID を支出に紐づけられないこと
- ダッシュボードに自分のデータのみ表示されること
- 作業日・支出日の曜日付き表示
- 作業時刻の整合性
- 作業時間の 10 分単位チェック

新機能の提案では、追加・変更すべき Feature Test の観点も出してください。

## 新機能検討時の回答ルール

私が新機能案を相談したら、次の形式で回答してください。

1. 仕様理解
   - その新機能が、カフェログのどの目的に効くかを整理してください。

2. 実装方針
   - 既存の責務分離に沿って、どのレイヤーを変更するか示してください。
   - Controller に寄せすぎず、入力検証は FormRequest、所有者チェックは Policy / query 制約で扱ってください。

3. DB 変更案
   - migration が必要か判断してください。
   - 新しいテーブル・カラムが必要な場合は、型、nullable、外部キー、削除時挙動を示してください。
   - `user_id` によるデータ分離が必要か必ず検討してください。

4. 画面・ルート案
   - 追加する route、Controller action、Blade view を示してください。
   - 一覧、登録、詳細、編集、削除が必要か、または既存画面への追加でよいか判断してください。

5. バリデーション・認可
   - FormRequest のルール案を出してください。
   - 他ユーザーのデータを紐づけられないようにする方法を示してください。

6. テスト案
   - 追加すべき Feature Test を箇条書きで示してください。
   - 正常系、未ログイン、他ユーザーデータ禁止、バリデーションエラーを含めてください。

7. 実装ステップ
   - 小さく安全に実装する順番を示してください。

8. 注意点・代替案
   - 将来拡張、既存設計との整合性、やりすぎになりそうな点があれば指摘してください。

回答は日本語で、実装者がそのまま作業に移れる具体度にしてください。
ただし、まだ情報が足りない場合は、最初に確認質問を最大 3 つまでしてください。

## 今回相談したい新機能・改善内容

ここに相談内容を書く:
```

## 使い方

1. 上のコードブロック全体を ChatGPT に貼り付ける
2. 最後の `ここに相談内容を書く:` の下に、新機能案や悩んでいることを書く
3. 必要に応じて、該当する既存コードや画面のスクリーンショットも追加する

## 相談内容の例

```text
月別レポート機能を追加したいです。
月ごとの作業時間、支出合計、支出種別ごとの内訳、会計未記録件数を確認できる画面を作りたいです。
既存設計に沿うなら、どのように実装するのがよいですか？
```

```text
支出を CSV エクスポートできるようにしたいです。
会計ソフトへ転記しやすい形式にしたいのですが、実装案とテスト観点を出してください。
```

```text
作業記録の開始時刻・終了時刻から、作業時間を自動計算する機能を追加するか迷っています。
現行設計との相性、メリット・デメリット、実装する場合の変更点を整理してください。
```
