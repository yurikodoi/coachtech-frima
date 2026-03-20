環境構築Dockerビルドgit clone <リンク>docker-compose up -d --buildLaravel環境構築docker-compose exec php bashcomposer installcp .env.example .env 、環境変数を変更php artisan key:generatephp artisan migratephp artisan db:seed開発環境お問い合わせ画面：http://localhost/ユーザー登録：http://localhost/registerphpMyAdmin：http://localhost:8080/使用技術(実行環境)PHP 8.1.34Laravel 8.xMySQL 8.0.26nginx 1.21.1Docker / Docker ComposeER図erDiagram
users ||--o{ items : "出品"
users ||--o{ likes : "いいね"
users ||--o{ comments : "コメント"
users ||--o{ orders : "購入"
items ||--o{ category_item : "カテゴリ紐付け"
categories ||--o{ category_item : "構成"
items ||--|| orders : "注文確定"

    users {
        bigint id PK
        string name
        string email UK
        string password
        string postcode
        string address
    }

    items {
        bigint id PK
        bigint user_id FK
        string name
        integer price
        text description
        integer condition
    }

    categories {
        bigint id PK
        string name
    }

    orders {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        string shipping_address
    }
