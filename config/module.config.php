<?php

return array(
    'js_library' => array(
        'path_log' => '',
        'error_exception' => 'error/errorcustom',
        'error_ajax_exception' => 'error/errorcustomajax.phtml',
        'js_error_manager' => true
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'jsMessage' => '\JS\Plugin\JSMessage',
            'jsResponse' => '\JS\Plugin\JSResponse',
            'jsDataTable' => '\JS\Plugin\DataTable'
        ),
        'factories' => array(
            'jsLog' => 'JS\Plugin\LogFactory',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'jstranslator' => 'JS\Translator\TranslatorServiceFactory',
            'JS\Service\BaseService' => 'JS\Service\BaseServiceFactory'
        ),
    ),
    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type' => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
                'text_domain' => 'js'
            ),
        ),
    ),
    'validators' => array(
        'invokables' => array(
            'JS\Validator\JSInt' => 'JS\Validator\JSInt',
            'JS\Validator\JSFloat' => 'JS\Validator\JSFloat',
            'JS\Validator\Cpf' => 'JS\Validator\Cpf',
            'JS\Validator\Cnpj' => 'JS\Validator\Cnpj',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'jsNumberFormat' => 'JS\View\Helper\JSNumberFormat',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
