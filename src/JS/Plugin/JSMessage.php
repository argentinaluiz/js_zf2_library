<?php

/**
 * Plugin baseado em Zend Framework 2 para enviar respostas
 * html, 
 * e array de consultas
 * para ser ordenada a consulta no banco de dados
 * @link http://datatables.net/examples/data_sources/server_side.html
 * @author Luiz Carlos <argentinaluiz@gmail.com>
 */

namespace JS\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use JS\Template\Messages\MessageFactory;
use JS\Template\Messages\MessageFactoryInterface;

class JSMessage extends AbstractPlugin {

    /**
     * @string Tipo template da mensagem
     * jquery, boostrap...
     */
    private $type = MessageFactoryInterface::MESSAGE_BOOTSTRAP;

    public function __invoke($type = null) {
        if ($type != null)
            $this->type = $type;
        return $this;
    }

    public function message($msg, $priority = null) {
        if (!is_array($msg)) {
            $priority = ($priority == null ? \JS\Template\Messages\MessageInterface::ERROR : $priority);
            return MessageFactory::message($msg, $this->type, $priority);
        }
        else
            return $this->messages($msg);
    }

    public function messages(array $msgs) {
        $result = "";
        if (count($msgs) > 0) {
            $ul = "<ul>";
            foreach ($msgs as $key => $value) {
                $ul.="<li>" . $value[key($value)] . "</li>";
            }
            $ul.= "</ul>";
            $result = MessageFactory::message($ul, $this->type, key($msgs[0]));
        }
        return $result;
    }

    public function messagesComplex(array $msgs) {
        $arrayMessages = $this->initListPriorityMessages();
        $result = "";
        foreach ($msgs as $key => $value) {
            $arrayMessages[key($value)][] = $value[key($value)];
        }

        foreach ($arrayMessages as $key => $value) {
            $tam = count($value);
            if ($tam > 0) {
                if ($tam == 1)
                    $result = MessageFactory::message($value[0], $this->type, $key);
                else {
                    $ul = "<ul>";
                    foreach ($value as $message)
                        $ul.="<li>" . $message . "</li>";
                    $ul.= "</ul>";
                    $result.=MessageFactory::message($ul, $this->type, $key);
                }
            }
        }
        return $result;
    }

    private function initListPriorityMessages() {
        $array = MessageFactory::create($this->type)->getListPriorityMessage();
        $newArray = array();
        foreach ($array as $value)
            $newArray[$value] = array();
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

}

