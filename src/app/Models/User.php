<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'postcode',
        'address',
        'building',
        'image',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function getProfileImageUrlAttribute()
    {
        $path = $this->attributes['image'] ?? null;
        if (empty($path)) {
            return asset('img/default-user.png');
        }
        if (strpos($path, 'http') === 0) {
            return $path;
        }
        return asset('storage/' . ltrim($path, '/'));
    }
}