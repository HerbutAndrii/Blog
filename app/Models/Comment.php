<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function likes() {
        return $this->hasMany(CommentLike::class);
    }

    public function isLikedByUser() {
        return $this->likes()->where('user_id', auth()->user()->id)->exists();
    }

    public function getDateAsCarbon() {
        return Carbon::parse($this->updated_at);
    }
}
