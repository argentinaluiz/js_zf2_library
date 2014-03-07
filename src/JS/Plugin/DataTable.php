<?php

/**
 * Plugin baseado em Zend Framework 2 para verificar as colunas de uma tabela
 * para ser ordenada a consulta no banco de dados
 * @link http://datatables.net/examples/data_sources/server_side.html
 * @author Luiz Carlos <argentinaluiz@gmail.com>
 */

namespace JS\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DataTable extends AbstractPlugin {

    /**
     * @param array $colunas Array com as colunas da tabela em respectiva ordem.
     * Exemplo:
     * $colunas=array(
     * 'nome',
     * 'sobrenome',
     * 'cidade'
     * )
     * No Data Table foi passado que somente sera ordernado pela coluna nome e sobrenome.
     * 
     * $retorno=array('nome' => 'ASC','sobrenome' => 'ASC')
     */
    public function getOrderBy($colunas = array()) {

        $params = $this->getController()->plugin('params');
        $sOrder = array();
        $iSortCol0 = $params->fromQuery('iSortCol_0', "");
        if ($iSortCol0 != "" && count($colunas) > 0) {
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

}

