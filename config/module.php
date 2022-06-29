<?php
return [
    'system' => [
        'Vendor' => [
            'enable' => true,
        ],
        'AdminManager' => [
            'enable' => true,
        ],
        'ModuleStore' => [
            'enable' => true,
        ],
        'Site' => [
            'enable' => true,
        ],
        'Blog' => [
            'enable' => true,
        ],
        'Nav' => [
            'enable' => true,
            'config' => [
                'position' => '[{"k":"head","v":"头部导航"},{"k":"foot","v":"底部导航"}]',
            ]
        ],
        'Partner' => [
            'enable' => true,
            'config' => [
                'position' => '[{"k":"home","v":"首页"}]',
            ],
        ],
        'Banner' => [
            'enable' => true,
        ],
    ],
];
