<?php
namespace Modules\Cruise\Models;

use App\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class CruiseCategory extends BaseModel
{
    use SoftDeletes;
    use NodeTrait;
    protected $table = 'bravo_cruise_category';
    protected $fillable = [
        'name',
        'content',
        'slug',
        'status',
        'parent_id'
    ];
    protected $slugField     = 'slug';
    protected $slugFromField = 'name';

    public static function getModelName()
    {
        return __("Cruise Category");
    }

    public static function searchForMenu($q = false)
    {
        $query = static::select('id', 'name');
        if (strlen($q)) {
            $query->where('name', 'like', "%" . $q . "%");
        }
        $a = $query->limit(10)->get();
        return $a;
    }
}