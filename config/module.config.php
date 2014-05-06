<?php

return [
    'js_library' => [
        'path_log' => '',
        'error_exception' => 'error/errorcustom',
        'error_ajax_exception' => 'error/errorcustomajax.phtml',
        'js_error_manager' => true
    ],
    'controller_plugins' => [
        'invokables' => [
            'jsMessage' => '\JS\Plugin\JSMessage',
            'jsResponse' => '\JS\Plugin\JSResponse',
        ],
        'factories' => [
            'jsLog' => 'JS\Plugin\LogFactory',
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'JS\Service\BaseAbstractServiceFactory',
        ],
        'factories' => [
            'jstranslator' => 'JS\Translator\TranslatorServiceFactory',
            'JS\Service\BaseService' => 'JS\Service\BaseServiceFactory'
        ],
    ],
    'translator' => [
        'locale' => 'pt_BR',
        'translation_file_patterns' => [
            [
                'type' => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
                'text_domain' => 'js'
            ],
        ],
    ],
    'validators' => [
        'invokables' => [
            'JS\Validator\JSInt' => 'JS\Validator\JSInt',
            'JS\Validator\JSFloat' => 'JS\Validator\JSFloat',
            'JS\Validator\Cpf' => 'JS\Validator\Cpf',
            'JS\Validator\Cnpj' => 'JS\Validator\Cnpj',
            'JSNotEmpty' => 'JS\Validator\NotEmpty'
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'jsNumberFormat' => 'JS\View\Helper\JSNumberFormat',
        ]
    ],
    'view_manager' => [
        'template_map' => include __DIR__ . '/../template_map.php',
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
