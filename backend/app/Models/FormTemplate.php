<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUrl;
use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    use HasPublicUrl;

    protected $fillable = ['title', 'category_group', 'format', 'language', 'file_path', 'sort_order', 'is_active'];
    protected $appends = ['file_url'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->publicUrl($this->file_path);
    }
}
