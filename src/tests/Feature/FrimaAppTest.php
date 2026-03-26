<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class FrimaAppTest extends TestCase
{
    use RefreshDatabase;
    public function test_top_page_displays_items()
    {
        $item = Item::factory()->create(['name' => 'テスト商品']);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }
    public function test_item_detail_page_displays()
    {
        $item = Item::factory()->create();
        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee($item->name);
    }
    public function test_guest_cannot_access_purchase_page()
    {
        $item = Item::factory()->create();
        $response = $this->get('/purchase/' . $item->id);
        $response->assertRedirect('/login');
    }
    public function test_item_exhibition_works()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $response = $this->actingAs($user)->post('/sell', [
            'name' => '自作パソコン',
            'description' => '爆速です',
            'price' => 50000,
            'condition' => '1',
            'category_id' => [$category->id],
            'item_image' => UploadedFile::fake()->create('pc.jpg', 100) 
        ]);
        $this->assertDatabaseHas('items', ['name' => '自作パソコン']);
    }
    public function test_purchase_success_records_to_orders_table()
    {
        $user = User::factory()->create([
            'postcode' => '123-4567',
            'address' => '東京都渋谷区'
        ]);
        $item = Item::factory()->create(['price' => 1000]);
        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'card',
        ]);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_address' => '東京都渋谷区'
        ]);
    }
    public function test_item_exhibition_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/sell', [])
            ->assertSessionHasErrors([
                'name', 
                'description', 
                'item_image', 
                'category_id', 
                'condition', 
                'price'
            ]);
    }
    public function test_profile_postcode_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/mypage/profile', ['postcode' => '1234567'])
             ->assertSessionHasErrors(['postcode' => '郵便番号はハイフンありの8文字で入力してください']);
    }
    public function test_shipping_address_change_reflects_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            'postcode' => '999-9999',
            'address' => '大阪府大阪市',
        ]);
        $this->actingAs($user)->get("/purchase/{$item->id}")
             ->assertSee('999-9999')
             ->assertSee('大阪府大阪市');
    }
}