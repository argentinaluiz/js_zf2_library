<?php

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => [
                    'host' => '127.0.0.1',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => 'root',
                    'dbname' => 'dbname',
                ],
                'doctrine_type_mappings' => [
                    'enum' => 'string'
                ],
            ]
        ]
    ]
];
