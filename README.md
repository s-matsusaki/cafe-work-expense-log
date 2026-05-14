<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# cafe-work-expense-log
フリーランス活動に伴うカフェ利用費、学習書籍、作業記録、画像を管理するLaravelアプリ

## 使用技術
Laravel
PHP-FPM
nginx
PostgreSQL
Docker / Docker Compose

## 初回セットアップ

### Laravelプロジェクトの作成

Mac（PC）本体にComposerをインストールせず、Docker上のComposerを使ってLaravelプロジェクトを作成します。

cd (プロジェクトを作成するディレクトリ)
docker run --rm \
  -v "$PWD":/app \
  -w /app \
  composer:2 \
  composer create-project laravel/laravel temp-laravel

以下のようなディレクトリになる
現在のディレクトリ/
├── .git
├── .gitignore
├── .DS_Store
└── temp-laravel/
    ├── app/
    ├── artisan
    ├── composer.json
    ├── .env
    └── ...

github作成の.gitignoreとlaravel作成の.gitignoreが競合するので、laravel側の.gitignoreにマージする

Laravelの中身をリポジトリ直下にコピーします。
cp -R temp-laravel/. .

一時フォルダを削除します。
rm -rf temp-laravel

### PHP-FPMコンテナの作成
Laravelを実行するPHPコンテナを作成するため、以下のDockerfileを用意します。

docker/php/Dockerfile

PHP-FPMを使用することで、nginxからPHP処理を受け取り、Laravelアプリケーションを実行します。

### nginx設定の作成
Laravel用のnginx設定ファイルを作成します。

docker/nginx/default.conf

Laravelでは、Web公開ディレクトリをプロジェクト直下ではなく public/ にする必要があります。

そのため、nginxの root は以下を参照するように設定しています。

/var/www/html/public

これにより、.env や app/ など、直接公開すべきではないファイルやディレクトリがWebから参照されないようにしています。

### PostgreSQLコンテナの作成
開発用データベースとして、PostgreSQLコンテナを使用します。

compose.yaml では、PostgreSQLのデータをDocker volumeに保存するようにしています。

volumes:
  postgres-data:

これにより、コンテナを停止・削除しても、volumeを削除しない限りDBデータは保持されます。

### .env のPostgreSQL設定
Laravelの .env をPostgreSQL用に設定します。

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=cafe_work_record
DB_USERNAME=laravel
DB_PASSWORD=secret

DB_HOST=postgres は、compose.yaml で定義しているPostgreSQLサービス名です。

Docker Compose内では、サービス名をホスト名として利用できます。

### Docker Composeで起動
以下のコマンドで、Dockerコンテナをビルドして起動します。

docker compose up -d --build

起動後、コンテナの状態を確認します。

docker compose ps

以下のコンテナが起動していればOKです。

nginx
php
postgres

### マイグレーション実行

DB接続確認を兼ねて、Laravelのマイグレーションを実行します。

PHPコンテナ内でLaravelのマイグレーションを実行します。

docker compose exec php php artisan migrate

1つ目の php は、compose.yaml に定義したサービス名です。

2つ目の php は、コンテナ内で実行するPHPコマンドです。

### ブラウザで表示確認
以下にアクセスして、Laravelの初期画面が表示されることを確認します。

http://localhost:8080

表示できれば、Docker開発環境の構築は完了です。