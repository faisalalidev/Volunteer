<?php

return [
    [
        'name' => 'Departments',
        'flag' => 'department.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'department.create',
        'parent_flag' => 'department.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'department.edit',
        'parent_flag' => 'department.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'department.destroy',
        'parent_flag' => 'department.index',
    ],
];
