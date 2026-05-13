<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUrl;
use Illuminate\Database\Eloquent\Model;

class NewsPost extends Model
{
    use HasPublicUrl;

    protected $fillable = ['title', 'category', 'published_at', 'image_path', 'format', 'body', 'external_url', 'sort_order', 'is_active'];
    protected $appends = ['image_url'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'published_at' => 'date:Y-m-d'];
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->publicUrl($this->image_path);
    }
}
