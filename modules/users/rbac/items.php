<?php
return [
    'createShop' => [
        'type' => 2,
        'description' => 'Create Shops',
    ],
    'createAuction' => [
        'type' => 2,
        'description' => 'Create Auctions',
    ],
    'updateShop' => [
        'type' => 2,
        'description' => 'Update Shops',
    ],
    'updateAuction' => [
        'type' => 2,
        'description' => 'Update Auctions',
    ],
    'updateUsers' => [
        'type' => 2,
        'description' => 'Update Users',
    ],
    'updateOwnShop' => [
        'type' => 2,
        'description' => 'Update own Shops',
        'ruleName' => 'isOwner',
        'children' => [
            'updateShop',
        ],
    ],
    'updateOwnLot' => [
        'type' => 2,
        'description' => 'Update own Shops',
        'ruleName' => 'isOwner',
        'children' => [
            'updateAuction',
        ],
    ],
    'guest' => [
        'type' => 1,
        'description' => 'Only-read',
        'ruleName' => 'userRole',
    ],
    'user' => [
        'type' => 1,
        'description' => 'User',
        'ruleName' => 'userRole',
        'children' => [
            'createShop',
            'updateOwnShop',
        ],
    ],
    'author' => [
        'type' => 1,
        'description' => 'Can create',
        'ruleName' => 'userRole',
        'children' => [
            'createAuction',
            'user',
            'updateOwnLot',
        ],
    ],
    'moder' => [
        'type' => 1,
        'description' => 'Can moderate',
        'ruleName' => 'userRole',
        'children' => [
            'updateShop',
            'updateAuction',
            'author',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Good',
        'ruleName' => 'userRole',
        'children' => [
            'updateUsers',
            'moder',
        ],
    ],
];
