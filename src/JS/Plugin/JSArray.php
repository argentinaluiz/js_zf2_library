<?php

/**
 * Plugin baseado em Zend Framework 2 com funcoes para manipular arrays()
 * @author Luiz Carlos <argentinaluiz@gmail.com>
 */

namespace JS\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class JSArray extends AbstractPlugin {

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
    public function array_change_keys(array $new_keys, array $arrays) {
        $tam = count($arrays);
        $new_array = array();
        for ($i = 0; $i < $tam; $i++) {
            $new_array[$i] = array_combine($new_keys, $arrays[$i]);
        }
        return $new_array;
    }

    /**
     * Cria um array 'value' => 'Text' baseado em um array com sub-arrays
     * com dois elementos
     * $arrays   = array( 
     *  array(
     *      'id'=>'1',
     *      'label'=>'laranja'
     *  )...
     * )<br/>
     * $retorno = array('1' => 'laranja');<br/>
     * @param array $arrays array com os valores
     * @return array
     */
    public function array_value_text(array $arrays) {
        $tam = count($arrays);
        $keys = array_keys($arrays[0]);
        $new_array = array();
        for ($i = 0; $i < $tam; $i++) {
            $new_array[$arrays[$i][$keys[0]]] = $arrays[$i][$keys[1]];
        }
        return $new_array;
    }

}
