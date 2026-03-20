coachtechフリマ

環境構築

Dockerのビルド・起動

docker-compose up -d --build



PHPコンテナ内でのセットアップ

docker-compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link



マイグレーション・シーディング

php artisan migrate:fresh --seed



使用技術（実行環境）

Framework: Laravel 8.x

Language: PHP 8.1.34

Database: MySQL 8.0.26 (laravel_db)

Infrastructure: Docker / Docker Compose

Testing: PHPUnit (EmailVerify, FrimaApp, ItemTest, LikeTest)

ER図

erDiagram
    users ||--o{ items : "出品"
    users ||--o{ likes : "いいね"
    users ||--o{ comments : "コメント"
    users ||--o{ orders : "購入"
    items ||--o{ category_item : "カテゴリ紐付け"
    categories ||--o{ category_item : "構成"
    items ||--|| orders : "注文確定"

    users {
        unsigned_bigint id PK
        string name
        string email UK
        string password
        string postcode
        string address
    }

    items {
        unsigned_bigint id PK
        unsigned_bigint user_id FK
        string name
        integer price
        text description
        integer condition
    }

    categories {
        unsigned_bigint id PK
        string name
    }

    orders {
        unsigned_bigint id PK
        unsigned_bigint user_id FK
        unsigned_bigint item_id FK
        string shipping_address
    }


URL

開発環境: http://localhost/