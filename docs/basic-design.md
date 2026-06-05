# カフェログ 基本設計書

作成日: 2026-06-02
対象: `cafe-work-expense-log` 現行コードベース

## 1. 目的

カフェログは、フリーランス・個人開発者が日々の作業場所、作業時間、支出、書籍購入、会計ソフトへの記録状況を一元管理するための Web アプリケーションである。

主な目的は次のとおり。

- カフェや自宅などで行った作業を、場所・日付・時間・カテゴリ付きで記録する
- カフェ代、書籍代、SaaS 代、交通費などの支出を記録する
- 支出が会計ソフトへ記録済みかどうかを管理する
- 作業記録・支出・書籍を関連付け、あとから振り返りやすくする
- 月次の作業時間、支出、未記録支出をダッシュボードで把握する

## 2. システム全体像

```mermaid
flowchart LR
    user["利用者\nフリーランス・個人開発者"]
    browser["ブラウザ"]
    app["カフェログ\nLaravel / Blade"]
    db["PostgreSQL\n作業・支出・書籍データ"]
    accounting["会計ソフト\nfreee など"]

    user --> browser
    browser --> app
    app --> db
    user -. "記録状況を確認して手入力" .-> accounting
    app -. "会計ソフト連携は未実装\n記録済みフラグのみ管理" .-> accounting
```

## 3. 業務範囲

```mermaid
flowchart TB
    root["カフェログ"]

    root --> auth["認証"]
    auth --> login["ログイン"]
    auth --> logout["ログアウト"]
    auth --> register["ユーザー登録\n本番では無効化可能"]

    root --> place["場所管理"]
    place --> placeCrud["場所の CRUD"]

    root --> work["作業記録管理"]
    work --> workCrud["作業記録の CRUD"]
    work --> workFilter["月・場所・カテゴリで絞り込み"]
    work --> workTotal["表示中の作業時間合計"]

    root --> expense["支出管理"]
    expense --> expenseCrud["支出の CRUD"]
    expense --> expenseFilter["月・種別・会計記録状態で絞り込み"]
    expense --> expenseTotal["表示中の支出合計"]
    expense --> expenseAccounting["会計ソフト記録済み / 未記録管理"]

    root --> book["書籍管理"]
    book --> bookCrud["書籍の CRUD"]
    book --> bookStatus["未読・読書中・読了・中断"]

    root --> dashboard["ダッシュボード"]
    dashboard --> monthlySummary["今月の作業・支出サマリ"]
    dashboard --> recentData["直近データ表示"]
```

## 4. 利用者とユースケース

| 利用者 | 利用目的 | 主な操作 |
|---|---|---|
| ログインユーザー | 自分の作業・支出・書籍を管理する | 場所登録、作業記録登録、支出登録、書籍登録、一覧確認、編集、削除 |
| 未ログインユーザー | アプリへログインする | ログイン、登録可能環境でのユーザー登録 |
| ポートフォリオ確認者 | アプリの動作を確認する | Basic 認証通過後、デモアカウントでログイン |

## 5. 機能構成

```mermaid
flowchart LR
    dashboard["ダッシュボード"]
    cafes["場所"]
    workSessions["作業記録"]
    expenses["支出"]
    books["書籍"]

    dashboard --> cafes
    dashboard --> workSessions
    dashboard --> expenses
    dashboard --> books

    cafes --> workSessions
    cafes --> expenses
    workSessions --> expenses
    books --> expenses

    expenses --> accounting["会計記録状態"]
```

各機能の位置づけは次のとおり。

| 機能 | 役割 | 主なデータ |
|---|---|---|
| ダッシュボード | 今月の活動状況と直近データを表示する | 作業時間合計、支出合計、未記録支出数、書籍数 |
| 場所管理 | 作業場所を管理する | 場所名、住所、最寄駅、メモ |
| 作業記録管理 | 作業した内容と時間を管理する | 作業日、場所、開始時刻、終了時刻、作業時間、カテゴリ |
| 支出管理 | 作業関連の支出と会計記録状態を管理する | 支出日、金額、種別、支払方法、会計記録状態、関連データ |
| 書籍管理 | 購入書籍と読書状態を管理する | タイトル、購入日、読書状態、メモ |

## 6. 画面構成

```mermaid
flowchart TD
    guest["未ログイン"]
    login["ログイン画面"]
    register["登録画面\n登録許可時のみ"]
    dashboard["ダッシュボード"]

    cafeIndex["場所一覧"]
    cafeCreate["場所登録"]
    cafeShow["場所詳細"]
    cafeEdit["場所編集"]

    workIndex["作業記録一覧"]
    workCreate["作業記録登録"]
    workShow["作業記録詳細"]
    workEdit["作業記録編集"]

    expenseIndex["支出一覧"]
    expenseCreate["支出登録"]
    expenseShow["支出詳細"]
    expenseEdit["支出編集"]

    bookIndex["書籍一覧"]
    bookCreate["書籍登録"]
    bookShow["書籍詳細"]
    bookEdit["書籍編集"]

    guest --> login
    guest --> register
    login --> dashboard
    register --> dashboard

    dashboard --> cafeIndex
    dashboard --> workIndex
    dashboard --> expenseIndex
    dashboard --> bookIndex

    cafeIndex --> cafeCreate
    cafeIndex --> cafeShow
    cafeShow --> cafeEdit

    workIndex --> workCreate
    workIndex --> workShow
    workShow --> workEdit

    expenseIndex --> expenseCreate
    expenseIndex --> expenseShow
    expenseShow --> expenseEdit

    bookIndex --> bookCreate
    bookIndex --> bookShow
    bookShow --> bookEdit
```

## 7. データモデル概要

```mermaid
erDiagram
    USERS ||--o{ CAFES : owns
    USERS ||--o{ WORK_SESSIONS : owns
    USERS ||--o{ EXPENSES : owns
    USERS ||--o{ BOOKS : owns

    CAFES ||--o{ WORK_SESSIONS : used_at
    CAFES ||--o{ EXPENSES : paid_at
    WORK_SESSIONS ||--o{ EXPENSES : relates_to
    BOOKS ||--o{ EXPENSES : purchased_by

    USERS {
        bigint id PK
        string name
        string email UK
        string password
    }

    CAFES {
        bigint id PK
        bigint user_id FK
        string name
        string address
        string nearest_station
        text memo
    }

    WORK_SESSIONS {
        bigint id PK
        bigint user_id FK
        bigint cafe_id FK
        date work_date
        time start_time
        time end_time
        string title
        integer work_minutes
        string category
        text memo
    }

    EXPENSES {
        bigint id PK
        bigint user_id FK
        bigint cafe_id FK
        bigint work_session_id FK
        bigint book_id FK
        date expense_date
        string title
        integer amount
        string expense_type
        string payment_method
        boolean accounting_recorded
        datetime accounting_recorded_at
        text accounting_memo
        text memo
    }

    BOOKS {
        bigint id PK
        bigint user_id FK
        string title
        date purchased_on
        string status
        text memo
    }
```

### データ設計方針

- 主要データはすべて `user_id` を持ち、ログインユーザー単位で分離する
- 場所・作業記録・書籍は支出へ任意で紐づける
- 場所や作業記録を削除しても支出履歴は残すため、支出側の関連外部キーは `nullOnDelete` とする
- ユーザーを削除した場合は、そのユーザーの場所・作業記録・支出・書籍も削除する

## 8. アプリケーション構成

```mermaid
flowchart TB
    routes["routes/web.php"]
    controllers["Controller\nDashboard / Cafe / WorkSession / Expense / Book / Auth"]
    requests["FormRequest\n入力検証"]
    policies["Policy\n所有者チェック"]
    models["Eloquent Model\nUser / Cafe / WorkSession / Expense / Book"]
    views["Blade View\n画面表示"]
    db["PostgreSQL"]

    routes --> controllers
    controllers --> requests
    controllers --> policies
    controllers --> models
    controllers --> views
    models --> db
```

### レイヤーの責務

| レイヤー | 役割 |
|---|---|
| Route | URL と Controller の対応付け、`auth` / `guest` ミドルウェア適用 |
| Controller | 画面表示、検索条件組み立て、登録・更新・削除の流れを制御 |
| FormRequest | 入力値の検証、時刻パーツの結合、関連 ID の妥当性確認 |
| Policy | 対象データがログインユーザー本人のものか判定 |
| Model | DB テーブルとの対応、リレーション、日付表示用アクセサ |
| Blade | 画面 HTML、共通レイアウト、フォーム部品 |

## 9. 実行環境構成

### 開発環境

```mermaid
flowchart LR
    browser["Browser\nlocalhost:8080"]
    nginx["nginx container\n:8080 -> :80"]
    php["php container\nPHP-FPM / Laravel"]
    postgres["postgres container\nPostgreSQL 16\nhost :15432"]
    node["node container / local npm\nVite :5173"]

    browser --> nginx
    nginx --> php
    php --> postgres
    node -. "CSS / JS build" .-> php
```

### 本番環境

```mermaid
flowchart LR
    user["利用者"]
    dns["Route 53"]
    ec2["AWS EC2"]
    nginx["nginx container\nBasic 認証 / HTTPS 終端連携"]
    php["php container\nLaravel"]
    postgres["postgres container\nDB ポート非公開"]

    user --> dns
    dns --> ec2
    ec2 --> nginx
    nginx --> php
    php --> postgres
```

## 10. 認証・認可方針

```mermaid
flowchart TD
    request["HTTP Request"]
    authCheck{"ログイン済み?"}
    guestPage["ログイン / 登録"]
    appPage["アプリ機能"]
    ownerCheck{"対象データの user_id が\nログインユーザーIDと一致?"}
    ok["処理継続"]
    forbidden["403 Forbidden"]

    request --> authCheck
    authCheck -- "No" --> guestPage
    authCheck -- "Yes" --> appPage
    appPage --> ownerCheck
    ownerCheck -- "Yes" --> ok
    ownerCheck -- "No" --> forbidden
```

認証・認可の主な方針は次のとおり。

- アプリ本体の画面は `auth` ミドルウェア配下に置く
- ログイン画面と登録画面は `guest` ミドルウェア配下に置く
- 本番環境では `ALLOW_USER_REGISTRATION=false` によりユーザー登録を無効化できる
- ログイン失敗はメールアドレスと IP アドレス単位でレート制限する
- 登録処理は IP アドレス単位でレート制限する
- 詳細・編集・更新・削除は Policy で所有者を確認する

## 11. 非機能要件

| 区分 | 方針 |
|---|---|
| セキュリティ | 認証必須、Policy による所有者チェック、登録無効化、レート制限、Basic 認証、DB ポート非公開 |
| 可用性 | 単一 EC2 + Docker Compose 構成。ポートフォリオ・個人用途のため大規模冗長化は対象外 |
| 保守性 | Controller / FormRequest / Policy / Model / Blade を分離し、責務を明確化 |
| テスト | Feature Test で CRUD、認証、認可、表示仕様、関連 ID の検証を確認 |
| 表示品質 | Tailwind CSS と Blade partial により、一覧・フォーム・ボタンの見た目を統一 |
| 運用 | 本番 `.env`、Docker Compose、HTTPS、Basic 認証、AWS Budgets によるコスト監視 |

## 12. 今後の拡張候補

```mermaid
flowchart LR
    current["現行機能"]
    graph["月別グラフ\nReact など"]
    csv["CSV エクスポート"]
    report["月別レポート"]
    attachment["領収書添付"]
    accountingList["会計未記録一覧の改善"]
    backup["バックアップ運用"]

    current --> graph
    current --> csv
    current --> report
    current --> attachment
    current --> accountingList
    current --> backup
```

現行設計では `expenses` に会計記録状態と関連データを集約しているため、CSV エクスポート、月別レポート、会計未記録支出一覧は比較的追加しやすい。
