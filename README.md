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
        string building
        string profile_image "プロフ画像用"
    }

    items {
        bigint id PK
        bigint user_id FK
        string name
        string brand
        integer price
        text description
        integer condition
        string image_url
        boolean is_sold "売却済フラグ"
    }

    categories {
        bigint id PK
        string name
    }

    orders {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        string shipping_postal_code
        string shipping_address
        string shipping_building
    }

    likes {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
    }

    comments {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        text content
    }
