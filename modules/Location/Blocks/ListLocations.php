<?php
namespace Modules\Location\Blocks;

use Modules\Template\Blocks\BaseBlock;
use Modules\Location\Models\Location;

class ListLocations extends BaseBlock
{
    function __construct()
    {
        $list_service = [];
        foreach (get_bookable_services() as $key => $service) {
            $list_service[] = ['id'   => $key,
                               'name' => ucwords($key)
            ];
        }
        $this->setOptions([
            'settings' => [
                [
                    'id'            => 'service_type',
                    'type'          => 'select',
                    'label'         => "<strong>".__('Service Type')."</strong>",
                    'values'        => $list_service,
                    "selectOptions"=> [
                        'hideNoneSelectedText' => "true"
                    ]
                ],
                [
                    'id'        => 'title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title')
                ],
                [
                    'id'        => 'desc',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Desc')
                ],
                [
                    'id'        => 'number',
                    'type'      => 'input',
                    'inputType' => 'number',
                    'label'     => __('Number Item')
                ],
                [
                    'id'            => 'layout',
                    'type'          => 'select',
                    'label'         => __('Style'),
                    'values'        => [
                        [
                            'id'   => 'style_1',
                            'name' => __("Style 1")
                        ],
                        [
                            'id'   => 'style_2',
                            'name' => __("Style 2")
                        ]
                    ],
                    "selectOptions"=> [
                        'hideNoneSelectedText' => "true"
                    ]
                ],
                [
                    'id'            => 'order',
                    'type'          => 'select',
                    'label'         => __('Order'),
                    'values'        => [
                        [
                            'id'   => 'id',
                            'name' => __("Date Create")
                        ],
                        [
                            'id'   => 'name',
                            'name' => __("Title")
                        ],
                    ],
                    "selectOptions"=> [
                        'hideNoneSelectedText' => "true"
                    ]
                ],
                [
                    'id'            => 'order_by',
                    'type'          => 'select',
                    'label'         => __('Order By'),
                    'values'        => [
                        [
                            'id'   => 'asc',
                            'name' => __("ASC")
                        ],
                        [
                            'id'   => 'desc',
                            'name' => __("DESC")
                        ],
                    ],
                    "selectOptions"=> [
                        'hideNoneSelectedText' => "true"
                    ]
                ],
            ]
        ]);
    }

    public function getName()
    {
        return __('List Locations');
    }

    public function content($model = [])
    {
        if(empty($model['order'])) $model['order'] = "id";
        if(empty($model['order_by'])) $model['order_by'] = "desc";
        if(empty($model['number'])) $model['number'] = 5;
        if (empty($model['service_type']))
            return '';
        $model_location = Location::query();
        $model_location->where("status","publish");
        $model_location->orderBy($model['order'], $model['order_by']);
        $list = $model_location->limit($model['number'])->get();
        $data = [
            'rows'         => $list,
            'title'        => $model['title'],
            'desc'         => $model['desc'] ?? "",
            'service_type' => $model['service_type'],
            'layout'       => !empty($model['layout']) ? $model['layout'] : "style_1",
        ];
        return view('Location::frontend.blocks.list-locations.index', $data);
    }
}