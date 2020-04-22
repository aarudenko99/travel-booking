<?php
namespace Modules\Tour\Models;

use App\BaseModel;

class TourTranslation extends BaseModel
{
    protected $table = 'bravo_tour_translations';
    protected $fillable = [
        'title',
        'content',
        'short_desc',
        'address',
        'faqs',
    ];
    protected $slugField     = false;
    protected $seo_type = 'tour_translation';
    protected $cleanFields = [
        'content'
    ];
    protected $casts = [
        'faqs'  => 'array',
    ];
    public function getSeoType(){
        return $this->seo_type;
    }
}