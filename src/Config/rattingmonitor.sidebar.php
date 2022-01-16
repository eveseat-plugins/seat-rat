<?php
return [
    'rattingmonitor' => [
        'name'          => 'Ratting Monitor',
        'icon'          => 'fas fa-cat',
        'route_segment' => 'ratting',
        'permission' => 'rattingmonitor.cat',
        'entries'       => [
            [
                'name'  => 'By Character',
                'icon'  => 'fas fa-user',
                'route' => 'rattingmonitor.character',
                'permission' => 'rattingmonitor.cat',
            ],
            [
                'name'  => 'By User',
                'icon'  => 'fas fa-users',
                'route' => 'rattingmonitor.user',
                'permission' => 'rattingmonitor.cat',
            ],
            [
                'name'  => 'About',
                'icon'  => 'fas fa-info',
                'route' => 'rattingmonitor.about',
                'permission' => 'rattingmonitor.cat',
            ]
        ]
    ]
];