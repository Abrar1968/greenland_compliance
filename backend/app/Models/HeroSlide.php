<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUrl;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasPublicUrl;

    protected $fillable = ['image_path', 'alt_text', 'sort_order', 'is_active'];
    protected $appends = ['image_url'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->publicUrl($this->image_path);
    }
}
