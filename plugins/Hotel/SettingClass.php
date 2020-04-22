<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/2/2019
 * Time: 9:37 AM
 */
namespace Plugins\Hotel;

use Modules\Core\Abstracts\BaseSettingsClass;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        return [
            [
                'position'=>30,
                'id'=>'space',
                'title'=>__("Hotel Settings"),
                'view'=>"hotel::admin.settings.space",
                "keys"=>[

                ],
                'html_keys'=>[

                ]
            ]
        ];
    }
}