<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUrl;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasPublicUrl;

    protected $fillable = ['name', 'role', 'description', 'image_path', 'profile_slug', 'sort_order', 'is_active'];
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
