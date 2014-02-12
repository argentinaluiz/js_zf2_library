<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => '127.0.0.1',
                    'port' => '3306',
                    'user' => 'user',
                    'password' => 'pass',
                    'dbname' => 'dbname',
                ),
                'doctrine_type_mappings' => array(
                    'enum' => 'string'
                ),
            )
        ),
        'configuration' => array(
            'orm_default' => array(
                'numeric_functions' => array(
                    'DATE_FORMAT' => 'JS\Doctrine\DateFormat'
                )
            )
        )
    )
);
