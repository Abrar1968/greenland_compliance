<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUrl;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasPublicUrl;

    protected $fillable = ['site_name', 'meta_title', 'meta_description', 'logo_path', 'primary_phone', 'primary_email', 'address', 'business_hours', 'footer_description', 'footer_cta_title', 'footer_cta_text', 'footer_cta_button_label', 'footer_cta_button_href', 'map_embed_url', 'copyright_text', 'office_image_path', 'company_presentation_file', 'how_we_work_video_url'];

    protected $appends = ['logo_url', 'office_image_url', 'company_presentation_url', 'presentation_url'];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->publicUrl($this->logo_path);
    }

    public function getOfficeImageUrlAttribute(): ?string
    {
        return $this->publicUrl($this->office_image_path);
    }

    public function getCompanyPresentationUrlAttribute(): ?string
    {
        return $this->publicUrl($this->company_presentation_file);
    }

    public function getPresentationUrlAttribute(): ?string
    {
        return $this->company_presentation_url;
    }
}
