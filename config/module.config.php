<?php

return array(
    'js_library' => array(
        'path_log' => '',
        'error_exception' => 'error/errorcustom',
        'ajax_exception' => 'error/errorcustomajax.phtml',
        'js_error_manager' => true
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'jsFormat' => '\JS\Plugin\Format',
            'jsArray' => '\JS\Plugin\JSArray',
            'jsMessage' => '\JS\Plugin\JSMessage',
            'jsResponse' => '\JS\Plugin\JSResponse',
            'jsDataTable' => '\JS\Plugin\DataTable'
        ),
        'factories' => array(
            'jsLog' => function($sm) {
                $config = $sm->getServiceLocator()->get('Config');
                $pathLog = $config['js_library']['path_log'];
                if (empty($pathLog))
                    throw new \Exception("Especique o arquivo para armazenar o log");
                $log = new \JS\Plugin\Log();
                $writer = 'production' == getenv('APPLICATION_ENV') ?
                        new \Zend\Log\Writer\Stream($pathLog) :
                        new Zend\Log\Writer\FirePhp();
                $logger = new \Zend\Log\Logger();
                $logger->addWriter($writer);
                $log->setLog($logger);
                return $log;
            },
        ),
    ),
    'validators' => array(
        'invokables' => array(
            'JS\Validator\JSInt' => 'JS\Validator\JSInt',
            'JS\Validator\JSFloat' => 'JS\Validator\JSFloat',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'formElementBootstrap' => 'JS\View\Helper\FormElementBootstrap',
            'formNumberBootstrap' => 'JS\View\Helper\FormNumberBootstrap',
            'jsNumberFormat' => 'JS\View\Helper\JSNumberFormat',
            'jsDateFormat' => 'JS\View\Helper\JSDateFormat',
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