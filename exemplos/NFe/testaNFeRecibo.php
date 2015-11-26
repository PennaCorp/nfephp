<?php
/*
 * Exemplo de solicitação da situação da NFe atraves do numero do
 * recibo de uma nota enviada e recebida com sucesso pelo SEFAZ
 */
require_once(dirname(__FILE__).'/../../libs/NFe/ToolsNFePHP.class.php');

$nfe     = new ToolsNFePHP;
$modSOAP = '2'; //usando cURL
$recibo  = '351000093461047'; //este é o numero do seu recibo mude antes de executar este script
$chave   = '35150912011430000198550010000097861010097861';
$tpAmb   = '2'; //homologação

header('Content-type: text/xml; charset=UTF-8');

if ($aResp = $nfe->getProtocol($recibo, $chave, $tpAmb, $retorno)) {

    //houve retorno mostrar dados
    print_r($aResp);

} else {

    //não houve retorno mostrar erro de comunicação
    echo "Houve erro !! $nfe->errMsg";

}
