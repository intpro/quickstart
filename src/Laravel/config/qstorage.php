<?php

return [

    'block' => [
        'title' => '123123123',
        'stringfields' => ['string','second_string'],
        'textfields'   => ['description'],
        'numbs'        => ['id'],
        'bools'        => ['showed'],
        'images'       => ['logo'],
        'groups' => [
            'first_group' =>[
                'stringfields' => ['string','second_string'],
                'textfields'   => ['description'],
                'numbs'        => ['number'],
                'bools'        => ['showed'],
                'images'       => ['logo'],
            ],
            'second_group' =>[
                'owner' => 'first_group',
                'stringfields' => ['string2'],
            ],
            'third_group' => [
                'owner' => 'second_group',
                'stringfields' => ['string','second_string'],
                'textfields'   => ['description'],
                'numbs'        => ['number'],
                'bools'        => ['showed'],
                'images'       => ['logo'],
            ]
        ]
    ],

    'dom_all_images' => [
        'groups' => [
            'images_set' => [
                'images'    => ['text_pict']
            ]
        ]
    ]

];
