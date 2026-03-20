<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
class ItemTest extends TestCase
{
    use RefreshDatabase;
    public function test_item_list_excludes_own_items()
    {
        $me = User::factory()->create([
            'name' => 'テスト太郎',
            'address' => '東京都新宿区',
        ]);
        $other = User::factory()->create();
        Item::factory()->create(['user_id' => $me->id, 'name' => '私の商品']);
        Item::factory()->create(['user_id' => $other->id, 'name' => '他人の商品']);
        $response = $this->actingAs($me)->get('/');
        $response->assertStatus(200); 
        $response->assertSee('他人の商品');
        $response->assertDontSee('私の商品');
    }
    public function test_item_search()
    {
        $user = User::factory()->create();
        Item::factory()->create(['user_id' => $user->id, 'name' => '腕時計']);
        Item::factory()->create(['user_id' => $user->id, 'name' => 'HDD']);
        $this->get('/?keyword=腕時計')
             ->assertSee('腕時計')
             ->assertDontSee('HDD');
    }
    public function test_item_detail_shows_multiple_categories()
    {
        $cat1 = Category::create(['name' => 'ファッション', 'content' => 'ファッション']);
        $cat2 = Category::create(['name' => '家電', 'content' => '家電']);
        $item = Item::factory()->create();
        $item->categories()->attach([$cat1->id, $cat2->id]); 
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('ファッション'); 
        $response->assertSee('家電'); 
    }
    public function test_item_detail_displays_metadata()
    {
        $item = Item::factory()->create(['condition' => '1']); 
        $user = User::factory()->create();
        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);
        $response = $this->get("/item/{$item->id}");
        $response->assertSee('1');
        $response->assertSee('1');
    }
}