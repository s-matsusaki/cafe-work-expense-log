# カフェログ

カフェでの作業記録、支出記録、会計ソフトへの記録状況を管理するためのWebアプリケーションです。

個人事業主・フリーランスとしての活動を想定し、作業時間やカフェ利用、経費記録を一元管理することで、日々の作業振り返りや確定申告準備を補助することを目的としています。



## 公開URL

https://cafe-log.azumaki.com

※ポートフォリオ確認用のため、Basic認証を設定しています。
デモアカウント情報は必要に応じて個別に共有します。



## 開発背景

フリーランスとして活動するにあたり、カフェでの作業時間、作業内容、カフェ代、書籍購入費などをまとめて管理したいと考え、本アプリを開発しました。

単なるメモアプリではなく、会計ソフトへの記録状況も管理できるようにすることで、確定申告準備時に「どの支出を記録済みか」を確認しやすくしています。

また、Laravelを用いた実務的なCRUD、認証、認可、バリデーション、テスト、本番公開までを一通り経験することも目的としています。



## 使用技術

区分:技術
バックエンド:PHP / Laravel
フロントエンド:Blade / Tailwind CSS
データベース:PostgreSQL
Webサーバー:nginx
実行環境:Docker / Docker Compose
テスト:PHPUnit / Laravel Feature Test
認可:Laravel Policy
バリデーション:FormRequest
フロントビルド:Vite
本番環境:AWS EC2 |
DNS:Amazon Route 53
HTTPS:Let's Encrypt / Certbot
コスト管理:AWS Budgets
DB確認:DBeaver



## 本番構成

本番環境は、AWS EC2上にDocker Composeで構築しています。

ユーザー
↓
Route 53
↓
EC2
↓
nginx コンテナ
↓
PHP-FPM コンテナ
↓
Laravel
↓
PostgreSQL コンテナ



## セキュリティ・公開範囲

本アプリは、一般公開サービスではなく、ポートフォリオ確認用および自分用の記録アプリとして公開しています。

そのため、本番環境では以下の制御を行っています。

- Basic認証によるアクセス制限
- 本番環境でのユーザー登録無効化
- ログイン・登録処理へのレート制限
- Laravel Policyによるユーザー所有データの認可制御
- `APP_DEBUG=false` による本番エラー情報の非表示
- DBポートを外部公開しない構成
- AWS Budgetsによるコスト監視



## 主な機能

### 認証機能

- ユーザー登録
- ログイン
- ログアウト
- 本番環境でのユーザー登録無効化
- ログイン・登録処理へのレート制限

### カフェ管理

- カフェ一覧
- カフェ登録
- カフェ詳細
- カフェ編集
- カフェ削除

### 作業記録管理

- 作業記録一覧
- 作業記録登録
- 作業記録詳細
- 作業記録編集
- 作業記録削除
- 作業月での絞り込み
- カフェでの絞り込み
- カテゴリでの絞り込み
- 表示中の作業時間合計

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
- カフェ・作業記録・書籍との紐づけ

### 書籍管理

- 書籍一覧
- 書籍登録
- 書籍詳細
- 書籍編集
- 書籍削除
- 読書状態の管理
  - 未読
  - 読書中
  - 読了
  - 中断
- 支出記録との紐づけ

### ダッシュボード

- 今月の作業時間合計
- 今月の支出合計
- 会計ソフト未記録の支出件数
- 読書中の書籍数
- 未読の書籍数
- 直近の作業記録
- 直近の支出
- 直近の書籍



## DB設計概要

現在の主要テーブルは以下です。

テーブル:役割
users:ユーザー情報
cafes:作業場所としてのカフェ情報
work_sessions:作業記録
expenses:支出記録
books:書籍記録

### リレーション概要

- User は複数の Cafe を持つ
- User は複数の WorkSession を持つ
- User は複数の Expense を持つ
- User は複数の Book を持つ
- Cafe は複数の WorkSession を持つ
- Cafe は複数の Expense を持つ
- WorkSession は Cafe に任意で紐づく
- WorkSession は複数の Expense を持つ
- Expense は Cafe / WorkSession / Book に任意で紐づく
- Book は複数の Expense を持つ

### 設計方針

支出記録は、カフェ代・書籍代・その他支出をまとめて扱えるようにしています。

`expenses` テーブルには、必要に応じて `cafe_id`、`work_session_id`、`book_id` を紐づけられるようにし、支出の発生元を後から確認できる構成にしています。

また、各データには `user_id` を持たせ、ログインユーザーごとにデータを分離しています。



## 実装上の工夫

### FormRequestによるバリデーション分離

Controller内に直接バリデーションを書くのではなく、FormRequestに分離しています。

- CafeRequest
- WorkSessionRequest
- ExpenseRequest
- BookRequest

これにより、Controllerの責務を処理の流れに寄せ、入力検証の見通しを良くしています。

### Policyによる認可制御

ユーザーが自分のデータのみ閲覧・編集・削除できるように、Laravel Policyを使用しています。

- CafePolicy
- WorkSessionPolicy
- ExpensePolicy
- BookPolicy

URLを直接変更して他ユーザーのデータへアクセスしようとしても、403で拒否するようにしています。

### 関連IDの所有者チェック

支出記録に紐づく `book_id` について、存在確認だけでなく、ログインユーザー本人の書籍であることもバリデーションで確認しています。

画面上に表示しないだけでなく、直接POSTされた場合も他ユーザーのデータを紐づけられないようにしています。

### Bladeの共通化

共通レイアウトを `layouts/app.blade.php` にまとめ、ナビゲーション、認証状態、メッセージ表示などを共通化しています。

また、支出種別selectや関連書籍selectなど、複数画面で使う部品はpartial化しています。

### Tailwind CSSによるUI整備

BladeテンプレートにTailwind CSSを導入し、画面全体のレイアウト、テーブル、フォーム、ボタン、ダッシュボードカードなどを整備しています。



## テスト

Laravel Feature Testを用いて、主要なCRUD処理と認証・認可制御を確認しています。

### 実装済みテスト

- CafeTest
- WorkSessionTest
- ExpenseTest
- BookTest
- DashboardTest

### 主なテスト内容

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
- 他ユーザーの書籍IDを支出に紐づけられないこと
- ダッシュボードに自分のデータのみ表示されること



### テスト用DB

開発用DBとは別に、テスト用DB `cafe_work_record_testing` を用意しています。

`.env.testing` を使用し、`RefreshDatabase` によってテストごとにDB状態をリセットしています。

docker compose exec php php artisan test



## 環境構築手順

### リポジトリをクローン
git clone git@github.com:s-matsusaki/cafe-work-expense-log.git
cd cafe-work-expense-log

### .env を作成・設定
cp .env.example .env

### Dockerコンテナをビルド・起動
docker compose up -d --build

### Composerをインストール
docker compose exec php composer install

### APP_KEY生成
docker compose exec php php artisan key:generate

### マイグレーション実行
docker compose exec php php artisan migrate

### フロントビルド / 開発サーバー起動
npm install
npm run dev

### アプリにアクセス
http://localhost:8080

### テスト用DB作成
docker compose exec postgres psql -U laravel -d cafe_work_record

CREATE DATABASE cafe_work_record_testing;

\q

### .env.testing を作成し、テスト用DBを指定。
APP_ENV=testing

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=cafe_work_record_testing
DB_USERNAME=laravel
DB_PASSWORD=xxxxxxxx

CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=array



## テスト実行

### すべてのテストを実行
docker compose exec php php artisan test

### 特定のテストのみ実行（例）
docker compose exec php php artisan test --filter=CafeTest



## デモデータ

### ポートフォリオ確認用に、デモユーザーとサンプルデータを作成するSeederを用意しています。
docker compose exec php php artisan db:seed --class=DemoUserSeeder
docker compose exec php php artisan db:seed --class=DemoDataSeeder

## 本番公開で実施した作業

本番公開にあたり、以下を実施しました。

- AWS EC2インスタンス作成
- Docker / Docker Compose導入
- 本番用 `compose.prod.yaml` 作成
- 本番用nginx設定作成
- Basic認証設定
- Route 53で独自ドメイン取得
- `cafe-log.azumaki.com` をEC2へ紐づけ
- `azumaki.com` から `cafe-log.azumaki.com` へのリダイレクト設定
- Let's Encrypt / CertbotによるHTTPS化
- 本番環境でのユーザー登録無効化
- ログイン・登録レート制限
- AWS Budgetsによるコスト監視
- デモユーザー / デモデータSeeder作成
- 自分用ユーザーSeeder作成


## 本番公開前チェックリスト

- [x] APP_ENV=production
- [x] APP_DEBUG=false
- [x] APP_URLを本番URLに設定
- [x] ALLOW_USER_REGISTRATION=false
- [x] 本番DB接続情報を設定
- [x] 本番APP_KEYを生成
- [x] 本番用Docker Compose設定
- [x] Basic認証設定
- [x] Route 53 DNS設定
- [x] HTTPS化
- [x] AWS Budgets設定
- [x] migrate実行
- [x] DemoUserSeeder / DemoDataSeeder実行
- [x] OwnerUserSeeder実行
- [x] config:cache / route:cache / view:cache実行
- [x] 本番環境での動作確認



## .env変更時の注意

`compose.prod.yaml` で `env_file: .env` を使用しているため、`.env` を変更した場合は既存コンテナに反映されないことがあります。

その場合は以下でコンテナを再作成します。

docker compose -f compose.prod.yaml down
docker compose -f compose.prod.yaml up -d --build



## 今後の改善予定

- Reactによるダッシュボードグラフ表示
- 支出登録フォームの動的UI改善
- 書籍・作業記録から関連支出を登録しやすくする導線追加
- CSVエクスポート機能
- 月別レポート機能
- 添付ファイル管理
- 会計ソフト記録済み支出の一覧改善
- 画面UIのさらなる改善
- バックアップ運用の整備