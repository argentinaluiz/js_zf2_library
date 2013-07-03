<?php

namespace util;

class ArrayConsulta {

    const INDEX_CONSULTA = "consulta";
    const INDEX_OPCAO_CONSULTA = "opcoes_consulta";
    const INDEX_NUM_REGISTROS_MOSTRAR = "num_registros_mostrar";
    const INDEX_INI_PAGINACAO = "iniciopaginacao";
    const INDEX_PAGINACAO = "paginacao";
    const INDEX_PAGINA = "pagina";

    private $ini_paginacao = null;
    private $num_registros_consultar = 0;
    private $array_consulta = null;

    public function __construct($Dados = null) {
        if ($Dados == null){
            $this->array_consulta[ArrayConsulta::INDEX_OPCAO_CONSULTA] = null;
            $this->array_consulta[self::INDEX_PAGINA]=1;
        }    
        else
         if(isset($Dados[ArrayConsulta::INDEX_CONSULTA]))
            $this->array_consulta = $Dados[ArrayConsulta::INDEX_CONSULTA];
    }

    public function getOpcaoConsulta() {
        return $this->array_consulta[ArrayConsulta::INDEX_OPCAO_CONSULTA];
    }

    public function setOpcaoConsulta($value) {
        $this->array_consulta[ArrayConsulta::INDEX_OPCAO_CONSULTA] = $value;
    }

    public function getValue($index) {
        return $this->array_consulta[$index];
    }

    public function setValue($index, $value) {
        return $this->array_consulta[$index] = $value;
    }

    public function getIniPaginacao() {
        return $this->ini_paginacao;
    }

    public function getNumRegistrosConsultar() {
        return $this->num_registros_consultar;
    }

    public function setIniPaginacao($var) {
        $this->ini_paginacao = $var;
    }

    public function setNumRegistrosConsultar($var) {
        $this->num_registros_consultar = $var;
    }

    public function setPaginacao($var) {
        $this->array_consulta[self::INDEX_PAGINACAO] = $var;
    }
    
    public function setPagina($var){
     $this->array_consulta[self::INDEX_PAGINA]=$var;  
    }

    public function isExists($index) {
        return isset($this->array_consulta[$index]);
    }

    /**
     * 
     */
    public function isLimit() {
        return $this->ini_paginacao === null ? false : true;
    }

}

?>