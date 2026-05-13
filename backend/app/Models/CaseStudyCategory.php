<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseStudyCategory extends Model
{
    protected $fillable = ['name', 'slug', 'sort_order'];

    public function caseStudies(): HasMany
    {
        return $this->hasMany(CaseStudy::class)->orderBy('sort_order');
    }
}
