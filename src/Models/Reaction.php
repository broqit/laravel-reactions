<?php

namespace Broqit\Laravel\Reactions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('reactions.table_name', 'custom_reactions'));
    }

    protected $fillable = ['user_id', 'guest_id', 'type'];

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }
}
