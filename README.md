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
    %% --------------------------------------------------
    %% リレーションシップ定義 (実データに基づく Foreign Key 関係)
    %% --------------------------------------------------

    %% ユーザーと外部情報
    users ||--o| profiles : "hasOne (user_id)"

    %% ユーザーのアクション
    users ||--o{ items : "出品する (user_id)"
    users ||--o{ orders : "購入する (user_id)"
    users ||--o{ comments : "コメントする (user_id)"
    users ||--o{ likes : "いいねする (user_id)"
    users ||--o{ mylists : "マイリスト保存 (user_id)"

    %% 商品に対するアクション・属性
    items ||--o{ comments : "コメントがつく (item_id)"
    items ||--o{ likes : "いいねがつく (item_id)"
    items ||--o{ mylists : "登録される (item_id)"
    items ||--o| orders : "購入される (item_id)"

    %% 商品とカテゴリ (多対多)
    items ||--o{ category_item : "中間テーブル (item_id)"
    categories ||--o{ category_item : "中間テーブル (category_id)"

    %% --------------------------------------------------
    %% エンティティ定義 (実データに基づく カラム名と型)
    %% --------------------------------------------------

    users {
        bigint id PK "Auto Increment"
        string name "ユーザー名"
        string email "メールアドレス (UK)"
        timestamp email_verified_at "メール確認日時"
        string password "ハッシュパスワード"
        string remember_token "ログイン保持用"
        timestamp created_at "作成日時"
        timestamp updated_at "更新日時"
    }

    profiles {
        bigint id PK "Auto Increment"
        bigint user_id FK "users.id (UK)"
        string postal_code "郵便番号"
        string address "住所"
        string building "建物名"
        string image_url "プロフィール画像URL"
        timestamp created_at
        timestamp updated_at
    }

    items {
        bigint id PK "Auto Increment"
        bigint user_id FK "出品者: users.id"
        string name "商品名"
        string brand "ブランド名"
        unsigned_integer price "価格"
        text description "商品説明"
        string image_url "商品画像URL"
        string condition "状態 (1:良好 2:目立った傷なし 等)"
        timestamp created_at
        timestamp updated_at
    }

    categories {
        bigint id PK "Auto Increment"
        string name "カテゴリ名 (ファッション, 家電 等)"
        timestamp created_at
        timestamp updated_at
    }

    category_item {
        bigint id PK "Auto Increment"
        bigint item_id FK "items.id"
        bigint category_id FK "categories.id"
        timestamp created_at
        timestamp updated_at
    }

    orders {
        bigint id PK "Auto Increment"
        bigint user_id FK "購入者: users.id"
        bigint item_id FK "items.id"
        string shipping_postal_code "配送先郵便番号"
        string shipping_address "配送先住所"
        string shipping_building "配送先建物名"
        timestamp created_at
        timestamp updated_at
    }

    comments {
        bigint id PK "Auto Increment"
        bigint user_id FK "users.id"
        bigint item_id FK "items.id"
        text comment "コメント内容"
        timestamp created_at
        timestamp updated_at
    }

    likes {
        bigint id PK "Auto Increment"
        bigint user_id FK "users.id"
        bigint item_id FK "items.id"
        timestamp created_at
        timestamp updated_at
    }

    mylists {
        bigint id PK "Auto Increment"
        bigint user_id FK "users.id"
        bigint item_id FK "items.id"
        timestamp created_at
        timestamp updated_at
    }
