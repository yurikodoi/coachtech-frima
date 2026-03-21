## 1. 開発環境

言語: PHP 8.1.34

フレームワーク: Laravel 8.x

データベース: MySQL 8.0.26

ツール: Docker / Docker Compose

## 2. 作成した機能

ユーザー認証機能

・ユーザー登録・ログイン・ログアウト

・メール認証機能

商品管理機能

・商品出品（画像アップロード・カテゴリー・状態・価格設定）

・商品一覧表示（おすすめ・マイリストの切り替え）

・商品詳細表示・検索機能

・ユーザーアクション機能

・いいね機能（マイリスト保存）

・コメント投稿機能

購入・決済機能

・Stripe API 連携（クレジットカード決済・コンビニ決済）

・購入履歴の管理

・プロフィール・配送先管理

・プロフィール編集（名前・画像・住所の設定）

・商品購入時の配送先変更機能（セッション管理）

## 3. 開発環境

アプリケーションURL: http://localhost/

ユーザー登録: http://localhost/register

phpMyAdmin: http://localhost:8080/


## 4. テストの実行

全てのテストケース（Feature/Unit）をパスすることを確認済みです。

docker-compose exec php php artisan test

## 5. ER図

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
