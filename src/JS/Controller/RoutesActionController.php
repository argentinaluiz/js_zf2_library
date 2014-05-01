<?php

namespace JS\Controller;

abstract class RoutesActionController extends AbstractActionController {

    private $routesAction = array(
        'save' => '',
        'save_and_new' => '',
        'save_and_close' => '',
        'delete' => ''
    );
    private $identifierName = 'codigo';
    private $route;

    public function addRoutesAction($routeAction, $url) {
        $this->routesAction[$routeAction] = $url;
    }

    protected function getIdentifierData($form, $data) {
        if (isset($data[$form->getBaseFieldset()->getName()][$this->getIdentifierName()]))
            return $data[$form->getBaseFieldset()->getName()][$this->getIdentifierName()];
        else
            return false;
    }

    protected function triggerRoutesAction($submitValue) {
        $this->initRoutesAction();
        $routes = $this->getRoutesAction();
        //Sem acoes de rotas incluidas ou nao presente no array de rotas default
        if (in_array($submitValue, array_keys($routes))) {
            $action = $routes[$submitValue];
            if (empty($action))
                $this->flashMessenger()->addMessage([
                    'notice' => "<strong>Rota não implementada</strong>"
                ]);
            else {
                if (is_callable($routes[$submitValue]))
                    return $this->redirect()->toUrl($routes[$submitValue]($this));
                else
                    return $this->redirect()->toUrl($routes[$submitValue]);
            }
        } else
            return false;
    }

    /**
     * @todo routes disponiveis => array(
     * save, save_and_new, save_and_close, delete
     * )
     */
    protected function initRoutesAction() {
        $routesAction = $this->getRoutesAction();
        if ($routesAction['save'] == '')
            $this->addRoutesAction('save', function($controller) {
                $entity = $controller->getEntity();
                if (is_callable([$entity, 'get' . ucfirst($controller->getIdentifierName())], true)) {
                    $getIdentifier = 'get' . ucfirst($controller->getIdentifierName());
                    $url = $controller->url()->fromRoute($controller->getRoute(), [
                        'action' => 'editar',
                        $controller->getIdentifierName() => $entity->{$getIdentifier}()
                    ]);
                    return $url;
                }
                throw new \BadMethodCallException("Não foi possível pegar o identificador. "
                . "Se a entidade não tem um método para pegar o identificador, "
                . "pode ser implementado uma interface de EntityIdentifierInterface");
            });

        if ($routesAction['save_and_close'] == '')
            $this->addRoutesAction('save_and_close', $this->url()->fromRoute($this->getRoute(), [
                        'action' => 'consultar',
            ]));

        if ($routesAction['save_and_new'] == '')
            $this->addRoutesAction('save_and_new', $this->url()->fromRoute($this->getRoute(), [
                        'action' => 'novo',
            ]));
    }

    public function getRoutesAction() {
        return $this->routesAction;
    }

    public function setRoutesAction($routesAction) {
        $this->routesAction = $routesAction;
        return $this;
    }

    public function getIdentifierName() {
        return $this->identifierName;
    }

    public function setIdentifierName($identifierName) {
        $this->identifierName = $identifierName;
        return $this;
    }

    public function getRoute() {
        return $this->route;
    }

    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }

}
