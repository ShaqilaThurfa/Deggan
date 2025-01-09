<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class News extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'content', 'image', 'user_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            $news->slug = $news->slug ?? Str::slug($news->title, '-');
        });

        static::updating(function ($news) {
            $news->slug = Str::slug($news->title, '-');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
