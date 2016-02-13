<?php

return [

    'someblock' => [
        'title' => 'Некий блок',
        'numbs' => ['raz', 'dva'],
        'stringfields' => ['tri', 'chetyre'],
        'groups' => [
            'firstgroup' => [
                'images' => ['somepict'],
            ],
            'secondgroup' => [
                'owner' => 'firstgroup',
                'images' => ['somepict'],
                'numbs' => ['a1', 'a2']
            ]

        ]
    ]
];
