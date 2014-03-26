<?php

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => [
                    'host' => '127.0.0.1',
                    'port' => '3306',
                    'user' => 'user',
                    'password' => 'pass',
                    'dbname' => 'dbname',
                ],
                'doctrine_type_mappings' => [
                    'enum' => 'string'
                ],
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'numeric_functions' => [
                    'DATE_FORMAT' => 'JS\Doctrine\DateFormat'
                ]
            ]
        ]
    ]
];
