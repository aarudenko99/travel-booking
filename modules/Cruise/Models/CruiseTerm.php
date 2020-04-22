<?php
namespace Modules\Cruise\Models;

use App\BaseModel;

class CruiseTerm extends BaseModel
{
    protected $table = 'bravo_cruise_term';
    protected $fillable = [
        'term_id',
        'cruise_id'
    ];
}