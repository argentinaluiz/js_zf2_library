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
    private $type;

    public function __invoke($type = MessageFactoryInterface::MESSAGE_BOOTSTRAP) {
        $this->type = $type;
    }

    public function message($msg, $priority = null) {
        if (!is_array($msg)) {
            $priority = ($priority == null ? \JS\Template\Messages\MessageInterface::ERROR : $priority);
            return MessageFactory::message($msg, $priority);
        }
        else
            return $this->messages($msg, $priority);
    }

    public function messages(array $msg, $priority = \JS\Template\Messages\MessageInterface::ERROR) {
        $result = "";
        foreach ($msg as $key => $value) {
            if (is_array($value)) {
                $ul = "<ul>";
                foreach ($value as $message)
                    $ul.="<li>" . $message . "</li>";
                $ul = "</ul>";
                $result = MessageFactory::message($ul, $this->type, $key);
            } else {
                $ul = "<ul>";
                foreach ($value as $message)
                    $ul.="<li>" . $message . "</li>";
                $ul = "</ul>";
                $result = MessageFactory::message($ul, $this->type, $priority);
            }
        }
        return $result;
    }

    public function messagesComplex(array $msgs, $priority = \JS\Template\Messages\MessageInterface::ERROR) {
        $arrayMessages = $this->initListPriorityMessages();
        $result = "";
        foreach ($msgs as $key => $value) {
            if (is_array($value))
                $arrayMessages[key($value)][] = $value[key($value)];
            else
                $arrayMessages[$priority] = $value;
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
                    $ul = "</ul>";
                    $result.=MessageFactory::message($ul, $this->type, $key);
                }
            }
        }
        return $result;
    }

    private function initListPriorityMessages() {
        $array = MessageFactory::create()->getListPriorityMessage();
        $newArray = array();
        foreach ($array as $value)
            $newArray[$value] = array();
    }

}

