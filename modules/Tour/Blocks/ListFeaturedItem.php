<?php
namespace Modules\Tour\Blocks;

use Modules\Template\Blocks\BaseBlock;
use Modules\Media\Helpers\FileHelper;

class ListFeaturedItem extends BaseBlock
{
    function __construct()
    {
        $this->setOptions([
            'settings' => [
                [
                    'id'          => 'list_item',
                    'type'        => 'listItem',
                    'label'       => __('List Item(s)'),
                    'title_field' => 'title',
                    'settings'    => [
                        [
                            'id'        => 'title',
                            'type'      => 'input',
                            'inputType' => 'text',
                            'label'     => __('Title')
                        ],
                        [
                            'id'        => 'sub_title',
                            'type'      => 'input',
                            'inputType' => 'textArea',
                            'label'     => __('Sub Title')
                        ],
                        [
                            'id'    => 'icon_image',
                            'type'  => 'uploader',
                            'label' => __('Image Uploader')
                        ],
                        [
                            'id'        => 'order',
                            'type'      => 'input',
                            'inputType' => 'number',
                            'label'     => __('Order')
                        ],
                    ]
                ],
                [
                    'id'            => 'style',
                    'type'          => 'select',
                    'label'         => __('Style'),
                    'values'        => [
                        [
                            'id'   => 'normal',
                            'name' => __("Normal")
                        ],
                        [
                            'id'   => 'style2',
                            'name' => __("Style 2")
                        ],
                        [
                            'id'   => 'style3',
                            'name' => __("Style 3")
                        ]
                    ],
                    "selectOptions"=> [
                        'hideNoneSelectedText' => "true"
                    ]
                ]
            ]
        ]);
    }

    public function getName()
    {
        return __('List Featured Item');
    }

    public function content($model = [])
    {
        return view('Tour::frontend.blocks.list-featured-item.index', $model);
    }
}