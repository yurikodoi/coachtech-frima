## 1. 機能一覧

ユーザー認証: 新規登録、ログイン、メール認証機能。

商品出品: 画像アップロード、複数カテゴリ選択、商品の状態設定。

商品一覧・検索: 商品名によるキーワード検索、タブ切り替え（おすすめ・マイリスト）。

商品詳細: 商品情報、コメント一覧、いいね機能。

決済機能: Stripe API を利用したクレジットカードおよびコンビニ決済。

配送先管理: 購入時の配送先住所変更機能。

## 2. 環境構築

Docker ビルド

git clone <レポジトリURL>
docker-compose up -d --build


Laravel セットアップ

docker-compose exec php bash
composer install
cp .env.example .env  # その後、.env 内の DB_HOST=db や Stripe キーを設定
php artisan key:generate
php artisan migrate:fresh --seed


## 3. 開発環境

アプリケーションURL: http://localhost/

ユーザー登録: http://localhost/register

phpMyAdmin: http://localhost:8080/

## 4. 使用技術（実行環境）

PHP: 8.1.34

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
    users ||--o{ items : "出品する"
    users ||--o{ orders : "購入する"
    users ||--o{ comments : "コメントする"
    users ||--o{ likes : "いいねする"
    users ||--o{ mylists : "保存する"
    
    items ||--o{ category_item : "カテゴリを持つ"
    categories ||--o{ category_item : "属する"
    
    items ||--|| orders : "売買成立"
    items ||--o{ comments : "コメントがつく"
    items ||--o{ likes : "いいねされる"
    items ||--o{ mylists : "リストに入る"

    users {
        bigint id PK
        string name
        string email UK
        string password
        string postcode
        string address
        string building
        string profile_image
    }

    items {
        bigint id PK
        bigint user_id FK "出品者"
        string name
        string brand
        integer price
        text description
        integer condition
        string image_url
        boolean is_sold
    }

    categories {
        bigint id PK
        string name
    }

    category_item {
        bigint id PK
        bigint item_id FK
        bigint category_id FK
    }

    orders {
        bigint id PK
        bigint user_id FK "購入者"
        bigint item_id FK
        string shipping_postal_code
        string shipping_address
        string shipping_building
    }

    comments {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        text content
    }

    likes {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
    }

    mylists {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
    }
