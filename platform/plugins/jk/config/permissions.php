<?php

return [
    [
        'name' => 'Jks',
        'flag' => 'jk.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'jk.create',
        'parent_flag' => 'jk.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'jk.edit',
        'parent_flag' => 'jk.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'jk.destroy',
        'parent_flag' => 'jk.index',
    ],
];
