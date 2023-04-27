<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'id_post',
        'id_user',
        'id_parent'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'id_post');
    }

    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'id_parent');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'id_parent')->orderByDesc('created_at');
    }
}
