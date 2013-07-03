<?php

return array(
    'js_library' => array(
        'path_log' => 'data/log/app.log',
        'path_error_exception' => __DIR__ . '/../view/error/errorcustom.phtml',
        'path_error_ajax_exception' => __DIR__ . '/../view/error/errorcustomajax.phtml',
        'firephp_disabled' => false
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'format' => '\JS\Plugin\Format',
            'msg' => '\JS\Plugin\Msg',
            'dataTable' => '\JS\Plugin\DataTable'
        ),
        'factories' => array(
            'log' => function($sm) {
                $config = $sm->getServiceLocator()->get('Config');
                $pathLog = $config['js_library']['path_log'];
                $log = new \JS\Plugin\Log();
                $writer = 'production' == getenv('APPLICATION_PATH') ?
                        new \Zend\Log\Writer\Stream(getenv('APPLICATION_PATH') . '/' . $pathLog) :
                        new Zend\Log\Writer\FirePhp();
                $logger = new \Zend\Log\Logger();
                $logger->addWriter($writer);
                $log->setLog($logger);
                return $log;
            },
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        )
    ),
);
