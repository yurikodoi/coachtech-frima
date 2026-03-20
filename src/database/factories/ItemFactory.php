<?php
namespace Database\Factories;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class ItemFactory extends Factory
{
    protected $model = Item::class;
    public function definition()
    {
        return [
            'user_id'     => User::factory(),
            'name'        => 'テスト商品',
            'price'       => 1000,
            'brand'       => 'テストブランド',
            'description' => 'テスト用の商品説明です。',
            'image_url'   => 'img/watch.jpg',
            'condition'   => 1,
            'is_sold'     => false,
        ];
    }
}