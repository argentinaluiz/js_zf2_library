<?php

namespace JS\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Format extends AbstractPlugin {

    /**
     * Pega as mensagens do formulario e as dispoe
     * em forma de lista ul,li
     * @param \Zend\Form\Form $form
     * @return string HTML
     */
    public function formattingFormErros($form) {
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
    public function formattingAdapterErros($adapter) {
        $msg = "";
        $array_message = $adapter->getMessages();
        foreach ($array_message as $message) {
            $msg.="<ul><li>" . $message . "</li></ul>";
        }
        return $msg;
    }

    /**
     * Combinar os indices <b>$keys</b> com os valores <b>$arrays</b><br/>
     * Exemplo:<br/>
     * $keys   = array('id','label');<br/>
     * $arrays = array(array('codigo' => '1', nome => 'laranja'));<br/>
     * O novo array sera array(<br/>
     *                         array(<br/>
     *                            'id' => '1',<br/> 
     *                            'label' => 'laranja')<br/>
     *                         )
     * @param array $new_keys array com indices do novo array
     * @param array $arrays array com os valores do novo array
     * @return array
     */
    public function formattingKey_Array(array $new_keys, array $arrays) {
        $tam = count($arrays);
        $new_array = array();
        for ($i = 0; $i < $tam; $i++) {
            $new_array[$i] = array_combine($new_keys, $arrays[$i]);
        }
        return $new_array;
    }

    /**
     * Transformar o valor dos indices <b>$keys[0]</b> em indices e
     * <b>$keys[1]</b> nos valores<br/>
     * Exemplo:<br/>
     * $keys   = array('id','label');<br/>
     * $keys   = array('id'=>'1','label'=>'laranja');<br/>
     * $arrays = array('1' => 'laranja');<br/>
     * @param array $arrays array com os valores
     * @return array
     */
    public function formattingData_Array(array $keys, array $arrays) {
        $tam = count($arrays);
        $keys = array_keys($arrays[0]);
        $new_array = array();
        for ($i = 0; $i < $tam; $i++) {
            $new_array[$arrays[$i][$keys[0]]] = $arrays[$i][$keys[1]];
        }
        return $new_array;
    }

}
