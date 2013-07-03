<?php

namespace JS\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Json\Encoder;

class Msg extends AbstractPlugin {

    private $event;

    //put your code here
    public function msgHtml($msg) {
        $this->getEvent()->
                getResponse()->
                getHeaders()->
                addHeaders(array(
                    'Content-Type' => 'text/html'
                ))->
                setContent($msg);
    }

    public function msgJson(array $array) {
        $json = Encoder::encode($array);
        $this->getEvent()->
                getResponse()->
                getHeaders()->
                addHeaders(array(
                    'Content-Type' => 'text/json'
                ))->setContent($json);
    }

    public function msgError($msg, $include_formatting = true) {
        $response = $this->getEvent()->
                getResponse()->
                setStatusCode(400);
        $response->getHeaders()->
                addHeaders(array(
                    'Content-Type' => 'text/html'
        ));
        if ($include_formatting)
            $response->setContent(
                    "<div class='alert alert-error' style='margin-top:10px'>" .
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                    $msg .
                    "</div>"
            );
        else
            $response->setContent($msg);
    }

    /**
     * Tooltip Twitter Bootstrap
     * @param string $message
     */
    public function msgTooltipTb($message, $type) {
        $div = "";
        switch ($type) {
            case "information":
                $div = "<div class='alert alert-success' style='margin-top:10px'>" .
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                        $message .
                        "</div>";
                break;
            case "error":
                $div = "<div class='alert alert-error' style='margin-top:10px'>" .
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                        $message .
                        "</div>";
                break;
            case "notice":
                $div = "<div class='alert alert-block' style='margin-top:10px'>" .
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                        $message .
                        "</div>";
                break;
        }
        return $div;
    }

    /**
     * Tooltip Twitter Bootstrap
     * @param array $msg Array do Flash Messenger
     */
    public function flashMsgTooltipTb(array $msg) {
        $div = "";
        /**
         * Namespace do Flash Messenger
         */
        if (isset($msg[0]))
            foreach ($msg[0] as $key => $message) {
                switch ($key) {
                    case "information":
                        $div = "<div class='alert alert-success' style='margin-top:10px'>" .
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                                $message .
                                "</div>";
                        break;
                    case "error":
                        $div = "<div class='alert alert-error' style='margin-top:10px'>" .
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                                $message .
                                "</div>";
                        break;
                    case "notice":
                        $div = "<div class='alert alert-block' style='margin-top:10px'>" .
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                                $message .
                                "</div>";
                        break;
                }
            }
        return $div;
    }

    /**
     * @param array $msg Array do Flash Messenger
     */
    public function msgTooltipJquery(array $msg) {
        $div = "";
        /**
         * Namespace do Flash Messenger
         */
        if (isset($msg[0]))
            foreach ($msg[0] as $key => $message) {
                switch ($key) {
                    case "information":
                        $div = "<div data-type='information'>" .
                                "<div data-type='information' class='ui-state-active ui-corner-all ui-sytle-msg' style='float:right'>" .
                                "<a href='javascript:;' onclick='$(this).parent().parent().empty()'>" .
                                "<span class='ui-icon ui-icon-closethick'>close</span>" .
                                "</a>" .
                                "</div>" .
                                "<div class='ui-state-active ui-corner-all ui-sytle-msg'>" .
                                "<span class='ui-icon ui-icon-info ui-style-icon'></span>" .
                                $message . "</div>" .
                                "</div>";
                        break;
                    case "error":
                        $div = "<div data-type='error'>" .
                                "<div data-type='error' class='ui-state-error ui-corner-all ui-sytle-msg' style='float:right'>" .
                                "<a href='javascript:;' onclick='$(this).parent().parent().empty()'>" .
                                "<span class='ui-icon ui-icon-closethick'>close</span>" .
                                "</a>" .
                                "</div>" .
                                "<div class='ui-state-error ui-corner-all ui-sytle-msg'>" .
                                "<span class='ui-icon ui-icon-notice ui-style-icon'></span>" .
                                $message . "</div>" .
                                "</div>";
                        break;
                    case "notice":
                        $div = "<div data-type='notice'>" .
                                "<div class='ui-state-highlight ui-corner-all ui-sytle-msg' style='float:right'>" .
                                "<a href='javascript:;' onclick='$(this).parent().parent().empty()'>" .
                                "<span class='ui-icon ui-icon-closethick'>close</span>" .
                                "</a>" .
                                "</div>" .
                                "<div class='ui-state-highlight ui-corner-all ui-sytle-msg'>" .
                                "<span class='ui-icon ui-icon-alert ui-style-icon'></span>" .
                                $message . "</div>" .
                                "</div>";
                        break;
                }
            }
        return $div;
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

