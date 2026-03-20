<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;
use URL;
class EmailVerifyTest extends TestCase
{
    use RefreshDatabase;
    public function test_verification_email_is_sent_after_registration()
    {
        Notification::fake();
        $user = User::factory()->create(['email_verified_at' => null]);
        event(new \Illuminate\Auth\Events\Registered($user));
        Notification::assertSentTo($user, VerifyEmail::class);
    }
    public function test_verification_notice_screen_can_be_rendered()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/email/verify');
        $response->assertStatus(200);
        $response->assertSee('メール'); 
    }
    public function test_email_can_be_verified_and_redirects_to_home()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $response = $this->actingAs($user)->get($verificationUrl);
        $response->assertRedirect('/?verified=1');
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}