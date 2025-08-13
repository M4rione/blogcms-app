<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'body', 'image', 'user_id', 'views', 'category_id'];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title) . '-' . uniqid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'post_user_likes')->withTimestamps();
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likedByUsers()->where('user_id', $user->id)->exists();
    }
}
