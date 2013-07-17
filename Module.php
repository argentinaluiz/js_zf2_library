<?php

namespace JS;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->registerJSErrorManager($e);
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

    public function registerJSErrorManager(MvcEvent $e) {
        $config = $e->getApplication()->getServiceManager()->get('Config');
        if ($config['js_library']['js_error_manager']) {
            $this->dispatchError($e);
            $this->registerErrorHandler($e);
            $this->registerShutdownError($e);
        }
    }

    public function dispatchError(MvcEvent $e) {
        $sharedManager = $e->getApplication()->getEventManager()->getSharedManager();
        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error', function($e) {
                    if ($e->getParam('exception')) {
                        if ($e->getApplication()->getRequest()->isXmlHttpRequest()) {
                            echo $e->getApplication()->getServiceManager()->get("Controller\Plugin\Manager")->
                                    get('msg')->flashMsgTooltipTb(array(
                                array('error' => $e->getParam('exception')->getMessage()
                            )));
                        } else {
                            ob_clean(); //Limpar a tela de erros do php
                            header('HTTP/1.1 400 Bad Request');
                            $exception = $e->getParam('exception');
                            $sm = $e->getApplication()->getServiceManager();
                            $config = $sm->get('Config');
                            $e->getApplication()->getServiceManager()->get('Controller\Plugin\Manager')->get('jsLog')->log($exception, 2);
                            $viewModel = new \Zend\View\Model\ViewModel(array(
                                'exception' => $exception
                            ));
                            if ($e->getRequest()->isXmlHttpRequest()) {
                                $viewModel->setTemplate($config['js_library']['error_ajax_exception']);
                                $e->getApplication()->getServiceManager()->get('ViewRenderer')->render($viewModel);
                            } else {
                                $viewModel->setTemplate($config['js_library']['error_exception']);
                                echo $e->getApplication()->getServiceManager()->get('ViewRenderer')->render($viewModel);
                            }
                            /*
                             * Com erros handler o codigo continua a ser executado,
                             * entao o exit para e so mostra os erros
                             */
                            exit();
                        }
                    }
                }
        );
    }

    public function registerErrorHandler(MvcEvent $e) {
        set_error_handler(
                function ($severity, $message, $filename, $lineno) use ($e) {
                    if (error_reporting() == 0) {
                        return;
                    }
                    if (error_reporting() & $severity) {
                        $exception = new \ErrorException($message, 0, $severity, $filename, $lineno);
                        $e->setParam('exception', $exception);
                        $e->getApplication()->getEventManager()->trigger('dispatch.error', $e);
                    }
                });
    }

    public function registerShutdownError(MvcEvent $e) {
        register_shutdown_function(function () use($e) {
                    $error = error_get_last();
                    if ($error != null && $error["type"] != E_DEPRECATED) {
                        $exception = new \ErrorException($error['message'], 0, 1, $error['file'], $error['line']);
                        $e->setParam('exception', $exception);
                        $e->getApplication()->getEventManager()->trigger('dispatch.error', $e);
                    }
                });
    }

}

