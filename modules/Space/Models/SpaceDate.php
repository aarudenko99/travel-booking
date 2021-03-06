<?php
namespace Modules\Space\Models;

use App\BaseModel;

class SpaceDate extends BaseModel
{
    protected $table = 'bravo_space_dates';

    protected $casts = [
        'person_types'=>'array'
    ];

    public static function getDatesInRanges($start_date,$end_date, $target_id = 0){
        if($target_id){
            return static::query()->where([
                ['start_date','>=',$start_date],
                ['end_date','<=',$end_date],
                ['target_id', $target_id],
            ])->take(100)->get();    
        }
        return static::query()->where([
            ['start_date','>=',$start_date],
            ['end_date','<=',$end_date],
        ])->take(100)->get();
    }
}