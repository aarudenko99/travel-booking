<?php
namespace Modules\Tour\Models;

use App\BaseModel;

class TourDate extends BaseModel
{
    protected $table = 'bravo_tour_dates';

    protected $casts = [
        'person_types'=>'array'
    ];

    public static function getDatesInRanges($start_date,$end_date){
        return static::query()->where([
            ['start_date','>=',$start_date],
            ['end_date','<=',$end_date],
        ])->take(100)->get();
    }
    public function saveMeta(\Illuminate\Http\Request $request)
    {
        $locale = $request->input('lang');
        $meta = TourMeta::where('tour_date_id', $this->id)->first();
        if (!$meta) {
            $meta = new TourMeta();
            $meta->tour_date_id = $this->id;
        }
        return $meta->saveMetaOriginOrTranslation($request->input() , $locale);
    }
}
