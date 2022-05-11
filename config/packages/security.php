<?php
// config/packages/security.php
use App\Entity\User;
use Symfony\Config\SecurityConfig;

$container->loadFromExtension('security', [
    'providers' => [
        'users' => [
            'entity' => [
                // the class of the entity that represents users
                'class'    => User::class,
                // the property to query by - e.g. email, username, etc
                'property' => 'email',

                // optional: if you're using multiple Doctrine entity
                // managers, this option defines which one to use
                //'manager_name' => 'customer',
            ],
        ],
    ],

    // ...
]);