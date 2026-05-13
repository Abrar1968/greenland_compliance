<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSetting extends Model
{
    protected $fillable = ['headline_line1', 'headline_line2', 'paragraph', 'cta1_label', 'cta1_href', 'cta2_label', 'cta2_href'];
}
