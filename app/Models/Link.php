<?php

namespace ManyLinks\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
