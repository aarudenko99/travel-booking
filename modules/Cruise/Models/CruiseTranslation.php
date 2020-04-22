<?php
namespace Modules\Cruise\Models;

use App\BaseModel;

class CruiseTranslation extends BaseModel
{
    protected $table = 'bravo_cruise_translations';
    protected $fillable = [
        'title',
        'content',
        'short_desc',
        'address',
        'faqs',
    ];
    protected $slugField     = false;
    protected $seo_type = 'cruise_translation';
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