<?php

namespace JS;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sharedManager = $e->getApplication()->getEventManager()->getSharedManager();
        $sm = $e->getApplication()->getServiceManager();
        $config = $sm->get('Config');

        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error', function($e) use ($sm) {
                    if ($e->getParam('exception')) {
                        $sm->get("Controller\Plugin\Manager")->
                                get("log")->
                                log($e->getParam('exception'), 2);
                        if ($e->getApplication()->getRequest()->isXmlHttpRequest()) {
                            echo $e->getApplication()->getServiceManager()->get("Controller\Plugin\Manager")->
                                    get('msg')->flashMsgTooltipTb(array(
                                array('error' => $e->getParam('exception')->getMessage()
                            )));
                            exit();
                        }
                    }
                }
        );
        if (!$config['firephp_disabled']) {
            set_error_handler(
                    function ($severity, $message, $filename, $lineno) use ($e) {
                        if (error_reporting() == 0) {
                            return;
                        }
                        if (error_reporting() & $severity) {
                            ob_clean();
                            $exception = new \ErrorException($message, 0, $severity, $filename, $lineno);
                            $log = $e->getApplication()->getServiceManager()->get("Controller\Plugin\Manager")->get("log");
                            if (getenv('APPLICATION_ENV') != 'production') {
                                require(__DIR__ . '/../../vendor/firephp/firephp-core/lib/FirePHPCore/fb.php');
                                fb($exception->getMessage(), \FirePHP::ERROR);
                                fb($exception);
                            } else {
                                $log->log($exception, 2);
                            }
                            header('HTTP/1.1 400 Bad Request');
                            if (!$e->getRequest()->isXmlHttpRequest())
                                include __DIR__ . '/view/error/errorcustom.phtml';
                            else
                                include __DIR__ . '/view/error/errorcustomajax.phtml';
                            exit();
                        }
                    });



            register_shutdown_function(function () use($e) {
                        $error = error_get_last();
                        if ($error != null && $error["type"] != E_DEPRECATED) {
                            ob_clean();
                            $exception = new \ErrorException($error['message'], 0, 1, $error['file'], $error['line']);
                            $log = $e->getApplication()->getServiceManager()->get("Controller\Plugin\Manager")->get("log");
                            if (getenv('APPLICATION_ENV') != 'production') {
                                require(__DIR__ . '/../../vendor/firephp/firephp-core/lib/FirePHPCore/fb.php');
                                fb($exception->getMessage(), \FirePHP::ERROR);
                                fb($exception);
                            } else {
                                $log->log($exception, 2);
                            }
                            header('HTTP/1.1 400 Bad Request');
                            $config = $e->getApplication()->getServiceManager()->get("Config");
                            if (!$e->getRequest()->isXmlHttpRequest())
                                include $config['path_error_exception'];
                            else
                                include $config['path_error_ajax_exception'];
                            exit();
                        }
                    });
        }
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
                'autoregister_zf' => true,
                'prefixes' => array(
                    'Zend_' => __DIR__ . "/../../vendor/zendframework/zendframework1/library/Zend"
                ),
            ),
        );
    }

}
