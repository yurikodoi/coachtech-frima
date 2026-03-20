<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
class AuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_registration_validation()
    {
        $this->post('/register', ['name' => ''])->assertSessionHasErrors(['name' => 'お名前を入力してください']);
        $this->post('/register', ['password' => '1234567', 'password_confirmation' => '1234567'])
             ->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }
    public function test_user_can_register_and_redirect_to_profile()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $response->assertRedirect('/mypage/profile');
    }
    public function test_login_logout_flow()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);
        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])
             ->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
    public function test_login_validation()
    {
        $this->post('/login', ['email' => '', 'password' => ''])
             ->assertSessionHasErrors(['email' => 'メールアドレスを入力してください', 'password' => 'パスワードを入力してください']);
        $this->post('/login', ['email' => 'wrong@example.com', 'password' => 'wrongpassword'])
             ->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }
    public function test_registration_validation_extra()
    {
        $this->post('/register', ['email' => ''])->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }
    public function test_navigation_menu_changes_by_auth_status()
    {
        $this->get('/')->assertSee('ログイン')->assertSee('会員登録')->assertDontSee('ログアウト');
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        if ($response->isRedirect()) {
            $response = $this->followRedirects($response);
        }
        $response->assertSee('ログアウト')
                ->assertSee('マイページ')
                ->assertSee('出品');
    }
}