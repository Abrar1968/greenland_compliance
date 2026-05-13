<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUrl;
use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    use HasPublicUrl;

    protected $fillable = ['banner_label', 'hero_heading_line1', 'hero_heading_line2', 'hero_paragraph', 'hero_image_path', 'hero_cta_label', 'hero_cta_href', 'overview_paragraph1', 'overview_paragraph2', 'overview_callout', 'mission_heading', 'mission_intro', 'approach_intro1', 'approach_intro2', 'footer_cta_text', 'footer_cta_button_label', 'footer_cta_button_href'];
    protected $appends = ['hero_image_url'];

    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->publicUrl($this->hero_image_path);
    }
}
