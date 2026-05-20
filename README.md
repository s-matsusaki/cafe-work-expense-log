# カフェログ

カフェでの作業記録、支出記録、会計ソフトへの記録状況を管理するためのWebアプリケーションです。

個人事業主・フリーランスとしての活動を想定し、作業時間やカフェ利用、経費記録を一元管理することで、日々の作業振り返りや確定申告準備を補助することを目的としています。

## 開発目的

このアプリは、以下を目的として開発しています。

- カフェでの作業内容・作業時間を記録する
- カフェ代や関連支出を記録する
- 会計ソフトに記録済みかどうかを管理する
- 月別・種別ごとの支出や作業時間を確認する
- Laravelを用いた実務的なCRUD、認証、認可、テスト実装の学習・ポートフォリオ化

## 使用技術

区分:技術
バックエンド:PHP / Laravel
フロントエンド:Blade
データベース:PostgreSQL
Webサーバー:nginx
実行環境:Docker / Docker Compose
テスト:PHPUnit / Laravel Feature Test
DB確認:DBeaver

## 機能一覧

### 認証機能

- ユーザー登録
- ログイン
- ログアウト
- ログインユーザーごとのデータ分離

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
- 時間換算表示

### 支出管理

- 支出一覧
- 支出登録
- 支出詳細
- 支出編集
- 支出削除
- 会計ソフト記録済み / 未記録での絞り込み
- 支出月での絞り込み
- 支出種別での絞り込み
- 表示中の支出合計
- 会計記録済み件数
- 会計未記録件数

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

### リレーション概要

- User は複数の Cafe を持つ
- User は複数の Book を持つ
- User は複数の WorkSession を持つ
- User は複数の Expense を持つ
- Cafe は複数の WorkSession を持つ
- Cafe は複数の Expense を持つ
- WorkSession は Cafe に紐づく
- WorkSession は複数の Expense を持つ
- Expense は Cafe / WorkSession / booksに任意で紐づく


### Dockerによる開発環境構築

ローカル開発環境では Docker Compose を使用し、以下の構成で環境を構築しています。

ブラウザ
↓
nginx
↓
PHP-FPM
↓
Laravel
↓
PostgreSQL

## 環境構築手順

### リポジトリをクローン
git clone git@github.com:s-matsusaki/cafe-work-expense-log.git
cd リポジトリ名

### Dockerコンテナをビルド・起動
docker compose up -d --build

### .env を作成・設定
.env.example をコピーして .env を作成してください。

### マイグレーション実行
docker compose exec php php artisan migrate

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

### テスト環境用のAPP_KEYを生成します。
docker compose exec php php artisan key:generate --env=testing

### テスト用DBにマイグレーションを実行します。
docker compose exec php php artisan migrate --env=testing

## テスト実行

### すべてのテストを実行
docker compose exec php php artisan test

### 特定のテストのみ実行
docker compose exec php php artisan test --filter=CafeTest
docker compose exec php php artisan test --filter=WorkSessionTest
docker compose exec php php artisan test --filter=ExpenseTest

## デモデータ

### ポートフォリオ確認用に、デモユーザーとサンプルデータを作成するSeederを用意しています。
docker compose exec php php artisan db:seed --class=DemoUserSeeder
docker compose exec php php artisan db:seed --class=DemoDataSeeder

## 本番公開前チェックリスト

- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] APP_URLを本番URLに設定
- [ ] ALLOW_USER_REGISTRATION=false
- [ ] 本番DB接続情報を設定
- [ ] 本番APP_KEYを生成
- [ ] migrate実行
- [ ] DemoUserSeeder / DemoDataSeeder 実行
- [ ] OwnerUserSeeder 実行
- [ ] Basic認証設定
- [ ] HTTPS化
- [ ] AWS Budgets設定
- [ ] テスト全件成功
- [ ] config:cache / route:cache / view:cache 実行