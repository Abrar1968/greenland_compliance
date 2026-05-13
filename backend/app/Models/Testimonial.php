<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUrl;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasPublicUrl;

    protected $fillable = ['author', 'role', 'quote', 'avatar_path', 'page', 'sort_order', 'is_active'];
    protected $appends = ['avatar_url'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->publicUrl($this->avatar_path);
    }
}
