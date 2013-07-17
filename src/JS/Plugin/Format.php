<?php

/**
 * Plugin baseado em Zend Framework 2 para formatar array de erros
 * de Zend\Form e Zend\Adapter
 * para ser ordenada a consulta no banco de dados
 * @link http://datatables.net/examples/data_sources/server_side.html
 * @author Luiz Carlos <argentinaluiz@gmail.com>
 */

namespace JS\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Format extends AbstractPlugin {

    /**
     * Pega as mensagens do formulario e as dispoe
     * em forma de lista ul,li
     * @param \Zend\Form\Form $form
     * @return string HTML
     */
    public function formErros($form) {
        $msg = "";
        $array_message = $form->getMessages();
        foreach ($array_message as $elemName => $messages) {
            foreach ($messages as $message) {
                $label = "";
                $elemName = $form->get($elemName);
                if ($elemName != null)
                    $label = $elemName->getLabel();
                $msg.="<ul><li>" . $label . " " . $message . "</li></ul>";
            }
        }
        return $msg;
    }

    /**
     * Pega as mensagens do adaptador e as dispoe
     * em forma de lista ul,li
     * @param \Zend\File\Transfer\Adapter\Http Description $adapter
     * @return string HTML
     */
    public function adapterErros($adapter) {
        $msg = "";
        $array_message = $adapter->getMessages();
        foreach ($array_message as $message) {
            $msg.="<ul><li>" . $message . "</li></ul>";
        }
        return $msg;
    }

}
