<?php

namespace JS\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DataTable extends AbstractPlugin {

    private $event;

    public function getOrderBy($colunas) {

        $params = $this->getController()->plugin('params');
        $sOrder = array();
        $iSortCol_0 = $params->fromQuery('iSortCol_0', "");
        if ($iSortCol_0 != "" && count($colunas) > 0) {
            $iSortingCols = intval($params->fromQuery('iSortingCols', 0));
            for ($i = 0; $i < $iSortingCols; $i++) {
                $iSortingCols_ = intval($params->fromQuery('iSortCol_' . $i, ""));
                $bSortable_ = $params->fromQuery('bSortable_' . $iSortingCols_, "false");
                if ($bSortable_ == "true") {
                    $sSortDir_ = $params->fromQuery('sSortDir_' . $i, "ASC");
                    $sOrder[$colunas[$iSortingCols_]] = $sSortDir_;
                }
            }
        }
        return $sOrder;
    }

    /**
     * Get the event
     *
     * @return \Zend\Mvc\MvcEvent
     * @throws Exception\DomainException if unable to find event
     */
    private function getEvent() {
        if ($this->event) {
            return $this->event;
        }

        $controller = $this->getController();
        if (!$controller instanceof \Zend\Mvc\InjectApplicationEventInterface) {
            throw new \Exception('Forward plugin requires a controller that implements InjectApplicationEventInterface');
        }

        $event = $controller->getEvent();
        if (!$event instanceof \Zend\Mvc\MvcEvent) {
            $params = array();
            if ($event) {
                $params = $event->getParams();
            }
            $event = new MvcEvent();
            $event->setParams($params);
        }
        $this->event = $event;

        return $this->event;
    }

}

