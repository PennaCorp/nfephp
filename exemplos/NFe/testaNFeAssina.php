<?php

require_once(dirname(__FILE__).'/../../libs/NFe/ToolsNFePHP.class.php');

$nfe  = new ToolsNFePHP;
$file = '35150912011430000198550010000097861010097861-nfe.xml';
$arq  = file_get_contents($file);

if ($xml = $nfe->signXML($arq, 'infNFe')) {
    echo $xml;
    file_put_contents($file, $xml);

} else {

    echo $nfe->errMsg;

}
