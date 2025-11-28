<?php

namespace Broqit\Laravel\Reactions\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Broqit\Laravel\Reactions\Traits\HasReactions;

class Comment extends Model
{
    use HasReactions;

    protected $fillable = ['content'];

    protected $table = 'comments';
}

