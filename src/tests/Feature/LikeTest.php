<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
class LikeTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_like_and_unlike_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $this->post("/item/{$item->id}/like")
             ->assertRedirect('/login');
        $this->actingAs($user)->post("/item/{$item->id}/like");
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);
        $this->actingAs($user)->post("/item/{$item->id}/like");
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);
    }
}