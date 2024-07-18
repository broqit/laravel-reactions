<?php

namespace Broqit\Laravel\Reactions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type'];

    public function reactable()
    {
        return $this->morphTo();
    }
}
