<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactDepartment extends Model
{
    protected $fillable = ['title', 'email', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
