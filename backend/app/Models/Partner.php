<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUrl;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasPublicUrl;

    protected $fillable = ['name', 'industry', 'location', 'description', 'logo_path', 'sort_order', 'is_active'];
    protected $appends = ['logo_url'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->publicUrl($this->logo_path);
    }
}
