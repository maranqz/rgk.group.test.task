<?php
return [
    'viewPosts' => [
        'type' => 2,
        'description' => 'View a posts',
    ],
    'viewPost' => [
        'type' => 2,
        'description' => 'View a post',
    ],
    'createPost' => [
        'type' => 2,
        'description' => 'Create a post',
    ],
    'updateOwnPost' => [
        'type' => 2,
        'description' => 'Update own post',
        'ruleName' => 'isAuthor',
        'children' => [
            'updatePost',
        ],
    ],
    'deleteOwnPost' => [
        'type' => 2,
        'description' => 'delete own post',
        'ruleName' => 'isAuthor',
        'children' => [
            'deletePost',
        ],
    ],
    'updatePost' => [
        'type' => 2,
        'description' => 'Update post',
    ],
    'deletePost' => [
        'type' => 2,
        'description' => 'delete post',
    ],
    'guest' => [
        'type' => 1,
        'children' => [
            'viewPosts',
        ],
    ],
    'user' => [
        'type' => 1,
        'children' => [
            'viewPost',
            'guest',
        ],
    ],
    'manager' => [
        'type' => 1,
        'children' => [
            'createPost',
            'updateOwnPost',
            'deleteOwnPost',
            'user',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'updatePost',
            'deletePost',
            'manager',
        ],
    ],
];
