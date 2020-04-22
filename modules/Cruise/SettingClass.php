<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/2/2019
 * Time: 10:26 AM
 */
namespace  Modules\Cruise;

use Modules\Core\Abstracts\BaseSettingsClass;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        return [
            [
                'id'   => 'cruise',
                'title' => __("Cruise Settings"),
                'position'=>20,
                'view'=>"Cruise::admin.settings.cruise",
                "keys"=>[
                    'cruise_page_search_title',
                    'cruise_page_search_banner',
                    'cruise_layout_search',
                    'cruise_location_search_style',
                    'cruise_enable_review',
                    'cruise_review_approved',
                    'cruise_enable_review_after_booking',
                    'cruise_review_number_per_page',
                    'cruise_review_stats',
                    'cruise_page_list_seo_title',
                    'cruise_page_list_seo_desc',
                    'cruise_page_list_seo_image',
                    'cruise_page_list_seo_share',

                    'cruise_booking_buyer_fees',
                ],
                'html_keys'=>[

                ]
            ]
        ];
    }
}