<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of String
 *
 * @author Luiz Carlos
 */
namespace util;
class StringUtil {
    //put your code here
    /**
    * Retira tag html e espaços em branco
    */
    public static function getNoHTML($texto){ return strip_tags(trim($texto) , '<(.*?)>'); }

    public static function gerarSenha()
    {
     $CaracteresAceitos = 'abcdefghijlxywzABCDZYWZ0123456789';
     $max = strlen($CaracteresAceitos)-1;
     $password = null;
     for($i=0; $i < 8; $i++)
      $password .= $CaracteresAceitos{mt_rand(0, $max)};
     return $password;
    }
    
    public static function getValoresNumericos($String)
    {
     $replace = array("-",")","("," ","/",".");
     $String=str_replace($replace,"",$String,$c);
     return $String;
    }
    
    static function getDataSemFormato($data)
    {
      $data=explode("/",$data);
      $data="{$data[2]}-{$data[1]}-{$data[0]}";
      return $data;
    }
 
    static function getDataFormatada($data)
    {
      $data=explode("-",$data);
      $data="{$data[2]}/{$data[1]}/{$data[0]}";
      return $data;
    }

    static function upper ($str) {
        $LATIN_UC_CHARS = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ";
        $LATIN_LC_CHARS = "àáâãäåæçèéêëìíîïðñòóôõöøùúûüý";
        $str = strtr ($str, $LATIN_LC_CHARS, $LATIN_UC_CHARS);
        $str = strtoupper($str);
        return $str;
    }

    static function lower ($str) {
        $LATIN_UC_CHARS = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ";
        $LATIN_LC_CHARS = "àáâãäåæçèéêëìíîïðñòóôõöøùúûüý";
        $str = strtr ($str, $LATIN_UC_CHARS,$LATIN_LC_CHARS);
        $str = strtolower($str);
        return $str;
    }

    static function retirar_acentos_caracteres_especiais($string) {
            $palavra = strtr($string, "ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
            $palavranova = str_replace("_", " ", $palavra);
            return $palavranova; 
    }

    static function calcularDiferencaDatas($DataMaior,$DataMenor)
    {
         #setando a primeira data  10/01/2008 
        $dia1 = mktime(0,0,0,substr($DataMenor,3,2),substr($DataMenor,0,2),substr($DataMenor,6,4));
        #setando segunda data 10/02/2008
        $dia2 = mktime(0,0,0,substr($DataMaior,3,2),substr($DataMaior,0,2),substr($DataMaior,6,4));
        #armazenando o valor da subtracao das datas
        $d3 = ($dia2-$dia1);
        #usando o roud para arrendondar os valores
        #converter o tempo em dias
        $dias = round(($d3/60/60/24));
        #converter o tempo em horas
        //$hrs = round(($d3/60/60));
        #converter o tempo em minutos
        //$mins = round(($d3/60));
        #exibindo  dias | repudizira na tela 31
        return $dias;
    }

    static function getCPFFormatado($value)
    {
     if(strlen($value)==11)
      return substr($value,0,3).'.'.substr($value,3,3).'.'.substr($value,6,3).'-'.substr($value,9,2);   
     else
      return $value;
    }
    
    static function getCNPJFormatado($value)
    {
     if(strlen($value)==14)
      return substr($value,0,2).'.'.substr($value,2,3).'.'.substr($value,5,3).'/'.substr($value,8,4)."-".
             substr($value,12,2);   
     else
      return $value;
    }
    
    static function getTelefoneFormatado($value)
    {
     if(strlen($value)==10)
      return '('.substr($value,0,2).') '.substr($value,2,4).'-'.substr($value,6,4);   
     else
      return $value;
    }
    
    static function getDataAtual()
    {
     setlocale(LC_TIME,'pt_BR','ptb');
     date_default_timezone_set('America/Sao_Paulo'); 
     return date("d/m/Y"); 
    }
    
    static function getMoedaAmericana($valorbrasileiro)
    {
     $tirar= array(".");
     $valorbrasileiro= str_replace($tirar, "", $valorbrasileiro, $b);
     $tirar= array(",");
     $valorbrasileiro= str_replace($tirar, ".", $valorbrasileiro, $b);   
     return $valorbrasileiro;   
    }
    
    static function getMoedaBrasileira($valoramericano)
    {
     return number_format($valoramericano, 2, ',', '.'); 
    }
}
  
?>
