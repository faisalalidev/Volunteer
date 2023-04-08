<?php

return [
    [
        'name' => 'Regions',
        'flag' => 'region.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'region.create',
        'parent_flag' => 'region.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'region.edit',
        'parent_flag' => 'region.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'region.destroy',
        'parent_flag' => 'region.index',
    ],
];
