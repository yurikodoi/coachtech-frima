<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
class CommentTest extends TestCase
{
    use RefreshDatabase;
    public function test_comment_validation()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);
        $item = Item::factory()->create();
        $url = "/item/{$item->id}/comment";
        $this->actingAs($user)
             ->post($url, ['comment' => ''])
            ->assertSessionHasErrors(['comment']);
        $this->actingAs($user)
            ->post($url, ['comment' => str_repeat('a', 256)])
            ->assertSessionHasErrors(['comment']);
        $this->actingAs($user)
            ->post($url, ['comment' => 'テストコメントです']);
        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'テストコメントです'
        ]);
    }
}