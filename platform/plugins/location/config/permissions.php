<?php

return [
    [
        'name' => 'Locations',
        'flag' => 'location.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'location.create',
        'parent_flag' => 'location.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'location.edit',
        'parent_flag' => 'location.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'location.destroy',
        'parent_flag' => 'location.index',
    ],
];
