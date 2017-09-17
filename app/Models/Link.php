<?php

namespace ManyLinks\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'id' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
