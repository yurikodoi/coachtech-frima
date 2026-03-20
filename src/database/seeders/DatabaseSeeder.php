<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::firstOrCreate(
            ['id' => 1],
            [
                'name'     => 'テストユーザー',
                'email'    => 'test@example.com',
                'password' => bcrypt('password'),
                'postcode' => '123-4567',
                'address'  => '東京都墨田区',
            ]
        );
        $this->call([
            ItemSeeder::class,
        ]);
    }
}