<?php
namespace Modules\Cruise\Models;

use App\BaseModel;

class CruiseCategoryTranslation extends BaseModel
{
    protected $table = 'bravo_cruise_category_translations';
    protected $fillable = [
        'name',
        'content',
    ];
    protected $cleanFields = [
        'content'
    ];
}