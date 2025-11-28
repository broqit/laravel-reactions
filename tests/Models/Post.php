<?php

namespace Broqit\Laravel\Reactions\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Broqit\Laravel\Reactions\Traits\HasReactions;

class Post extends Model
{
    use HasReactions;

    protected $fillable = ['title', 'content'];

    protected $table = 'posts';
}

