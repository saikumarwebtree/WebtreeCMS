<?php

namespace Webtree\WebtreeCms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'status',
        'meta_title', 'meta_description',
        'published_at', 'user_id'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->slug = $post->slug ?? Str::slug($post->title);
        });
    }
}