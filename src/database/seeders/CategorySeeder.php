<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'ファッション'],
            ['name' => '家電'],
            ['name' => 'インテリア'],
            ['name' => 'レディース'],
            ['name' => 'メンズ'],
            ['name' => 'コスメ'],
            ['name' => '本'],
            ['name' => 'おもちゃ'],
            ['name' => 'ベビー・キッズ'],
            ['name' => 'レジャー'],
            ['name' => 'ハンドメイド'],
            ['name' => 'クリーニング'],
            ['name' => 'スポーツ'],
            ['name' => 'キッチン'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
