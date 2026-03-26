プロジェクト名：フリマアプリ (COACHTECH FRIMA)

このプロジェクトは、Laravel と Docker を使用して構築された、ユーザー間で商品の売買ができるフルスタックのフリマアプリケーションです。

## 1. 機能一覧

ユーザー認証: 新規登録、ログイン、メール認証機能。

商品出品: 画像アップロード、複数カテゴリ選択、商品の状態設定。

商品一覧・検索: 商品名によるキーワード検索、タブ切り替え（おすすめ・マイリスト）。

商品詳細: 商品情報、コメント一覧、いいね機能。

決済機能: Stripe API を利用したクレジットカードおよびコンビニ決済。

配送先管理: 購入時の配送先住所変更機能。

## 2. 環境構築手順

　1. リポジトリのクローン
 
git clone git@github.com:yurikodoi/coachtech-frima.git
cd coachtech-frima

　2. 環境設定ファイルの準備
 
cd src
cp .env.example .env

　3. Docker コンテナの起動

 
docker-compose up -d --build

　4. 依存ライブラリのインストールとキー生成

 
docker-compose exec php composer install
docker-compose exec php php artisan key:generate

　5. シンボリックリンクの作成

 
docker-compose exec php php artisan storage:link

　6. データベースの構築と初期データの投入

 
docker-compose exec php php artisan migrate:fresh --seed

## 3. 開発環境

アプリケーションURL: http://localhost/

ユーザー登録: http://localhost/register

phpMyAdmin: http://localhost:8080/

## 4. 使用技術（実行環境）

PHP: 8.4.x (fpm)

Framework: Laravel 8.x

Database: MySQL 8.0.26

Web Server: nginx 1.21.1

Infrastructure: Docker / Docker Compose

Payment: Stripe API

## 5. テストの実行

全てのテストケース（Feature/Unit）をパスすることを確認済みです。

docker-compose exec php php artisan test

## 6. ER図

```mermaid

erDiagram
    users ||--o{ items : "出品"
    users ||--o{ likes : "いいね"
    users ||--o{ comments : "コメント"
    users ||--o{ orders : "購入"
    users ||--o{ mylists : "マイリスト保存"
    items ||--o{ category_item : "カテゴリ紐付け"
    items ||--o{ mylists : "マイリスト登録"
    categories ||--o{ category_item : "構成"
    items ||--|| orders : "注文確定"

    users {
        bigint id PK "NOT NULL"
        varchar name "NOT NULL"
        varchar email UK "NOT NULL"
        timestamp email_verified_at "NULLABLE"
        varchar password "NOT NULL"
        varchar postcode "NULLABLE"
        varchar address "NULLABLE"
        varchar building "NULLABLE"
        varchar profile_image "NULLABLE"
        varchar remember_token "NULLABLE"
        timestamp created_at
        timestamp updated_at
    }

    items {
        bigint id PK "NOT NULL"
        bigint user_id FK "NOT NULL"
        varchar name "NOT NULL"
        varchar brand "NULLABLE"
        int price "NOT NULL"
        text description "NOT NULL"
        varchar image_url "NOT NULL"
        varchar condition "NOT NULL"
        tinyint is_sold "DEFAULT 0"
        timestamp created_at
        timestamp updated_at
    }

    categories {
        bigint id PK "NOT NULL"
        varchar name "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    orders {
        bigint id PK "NOT NULL"
        bigint user_id FK "NOT NULL"
        bigint item_id FK "NOT NULL"
        varchar shipping_postal_code "NOT NULL"
        varchar shipping_address "NOT NULL"
        varchar shipping_building "NULLABLE"
        timestamp created_at
        timestamp updated_at
    }

    mylists {
        bigint id PK "NOT NULL"
        bigint user_id FK "NOT NULL"
        bigint item_id FK "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    likes {
        bigint id PK "NOT NULL"
        bigint user_id FK "NOT NULL"
        bigint item_id FK "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    comments {
        bigint id PK "NOT NULL"
        bigint user_id FK "NOT NULL"
        bigint item_id FK "NOT NULL"
        text comment "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }

    category_item {
        bigint id PK "NOT NULL"
        bigint category_id FK "NOT NULL"
        bigint item_id FK "NOT NULL"
        timestamp created_at
        timestamp updated_at
    }
