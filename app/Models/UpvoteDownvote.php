<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpvoteDownvote extends Model
{
    use HasFactory;

    protected $fillable = ['is_upvoted', 'id_post', 'id_user'];
}
