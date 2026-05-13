<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApproachCard extends Model
{
    protected $fillable = ['title', 'icon', 'description', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
