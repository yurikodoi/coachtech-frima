<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Item extends Model
{
    use HasFactory;
    protected $casts = [
        'is_sold' => 'boolean',
    ];
    protected $fillable = [
        'user_id', 'category_id', 'name', 'brand', 'price', 
        'description', 'image_url', 'condition', 'is_sold'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function isSoldOut(): bool
    {
        return $this->is_sold === true;
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }
    public function is_liked_by_auth_user()
    {
        if (!auth()->check()) return false;
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function getImageUrlAttribute()
    {
        $path = $this->attributes['image_url'] ?? null;
        if (empty($path)) {
            return asset('img/watch.jpg');
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        if (str_contains($path, 'img/')) {
            return asset(ltrim($path, '/'));
        }
        return asset('storage/' . ltrim($path, '/'));
    }
    public function getConditionTextAttribute()
    {
        $conditions = [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];
        return $conditions[$this->condition] ?? $this->condition;
    }
}