<?php
    require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."libs".DIRECTORY_SEPARATOR."NFe".DIRECTORY_SEPARATOR."ToolsNFePHP.class.php");
    //require_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."util".DIRECTORY_SEPARATOR."Functions.php");
    class PennaCorpNFe extends ToolsNFePHP{
        private $pennaCorpCertificate;
        private $aConfig = array();
        public function __construct(PennaCorpCertificate $pennaCorpCertificate){
            $con = con();
            $this->pennaCorpCertificate = $pennaCorpCertificate;
            $query = "select EM_RAZAO, EM_UF, EM_CGC, EM_GRUPO, ";
            $query .= "(select AF_STRING from auxfilho where ap_nome='NFE.XML' and AF_CODIGO='PRODUCAO')";
            $query .= " as AMBIENTE from empresa where em_number='".$pennaCorpCertificate->getEmpresa()."' and EM_LIGADA='T'";
            $result = mysqli_query($con, $query);
            if (mysqli_num_rows($result) == 0){
                throw new nfephpException("Empresa não encontrada ou não está ligada");
            }
            $row = mysqli_fetch_array($result);
            if ($row['EM_GRUPO'] != "SMT"){
                throw new nfephpException("O tipo da empresa escolhida não é SMT, essa empresa não emite NFe");
            }
            $row['EM_CGC'] = preg_replace("/[^0-9]/", "", $row['EM_CGC']);
            if (strlen($row['EM_CGC']) < 14){
                throw new nfephpException("O CNPJ da empresa informada não é válido.");
            }
            $aConfigLoc = array();
            /*
            *DEFINIDO PELO nfephp
            *1-Ambiente de produção
            *2-Ambiente de homologação
            */
            $aConfigLoc['ambiente'] = ($row['AMBIENTE'] == 'PROD' ? 1 : 2);
            $aConfigLoc['empresa'] = $row['EM_RAZAO'];
            $aConfigLoc['UF'] = $row['EM_UF'];
            $aConfigLoc['cnpj'] = $row['EM_CGC'];
            //Por segurança, o nome do certificado da empresa é criptografado com MD5
            /*definir gravação de senha*/
            /*$priKEY
        $pubKEY
        $certKEY*/
            $aConfigLoc['pubKey'] = $pennaCorpCertificate->getPubKey();
            $aConfigLoc['priKey'] = $pennaCorpCertificate->getPriKey();
            $aConfigLoc['certKey'] = $pennaCorpCertificate->getCertKey();
            $aConfigLoc['keyPass'] = $pennaCorpCertificate->getPassword();
            $aConfigLoc['passPhrase'] = "";
            $aConfigLoc['arquivosDir'] = "D:\\ECLIPSE\\Icarus\\component\\nfephp\\nfe";
            $aConfigLoc['arquivoURLxml'] = "nfe_ws3_mod55.xml";
            $aConfigLoc['baseurl'] = "http://localhost/nfephp";
            $aConfigLoc['danfeLogo'] = "";
            $aConfigLoc['danfeLogoPos'] = "L";
            $aConfigLoc['danfeFormato'] = "P";
            $aConfigLoc['danfePapel'] = "A4";
            $aConfigLoc['danfeCanhoto'] = 1;
            $aConfigLoc['danfeFonte'] = "Times";
            $aConfigLoc['danfePrinter'] = "hpteste";
            $aConfigLoc['schemes'] = "PL_008c";
            $aConfigLoc['proxyIP'] = "";
            $aConfigLoc['mailFROM'] = "";
            $this->aConfig = $aConfigLoc;
            parent::__construct($aConfigLoc);
        }
        public function addProtocolo($xmlNfe, $xmlProt){
            $docnfe = new DOMDocument();
            $docnfe->loadXML($xmlNfe);
            $nodenfe = $this->getNode($docnfe, 'NFe', 0);
            //carrega o protocolo
            $docprot = new DOMDocument();
            $docprot->loadXML($xmlProt);
            $nodeprots = $docprot->getElementsByTagName('protNFe');
            //carrega dados da NFe
            $tpAmb = $this->getNodeValue($docnfe, 'tpAmb');
            $infNFe = $this->getNode($docnfe, "infNFe", 0);
            $versao = $infNFe->getAttribute("versao");
            $chaveId = $infNFe->getAttribute("Id");
            $chaveNFe = preg_replace('/[^0-9]/', '', $chaveId);
            $digValueNFe = $this->getNodeValue($docnfe, 'DigestValue');
            //carrega os dados do protocolo
            $nodeprot = $nodeprots->item(0);
            $protver = $nodeprot->getAttribute("versao");
            $chaveProt = $nodeprot->getElementsByTagName("chNFe")->item(0)->nodeValue;
            $digValueProt = ($nodeprot->getElementsByTagName("digVal")->length)
                ? $nodeprot->getElementsByTagName("digVal")->item(0)->nodeValue
                : '';
            $infProt = $nodeprot->getElementsByTagName("infProt")->item(0);
            //cria a NFe processada com a tag do protocolo
            $procnfe = new DOMDocument('1.0', 'utf-8');
            $procnfe->formatOutput = false;
            $procnfe->preserveWhiteSpace = false;
            //cria a tag nfeProc
            $nfeProc = $procnfe->createElement('nfeProc');
            $procnfe->appendChild($nfeProc);
            //estabele o atributo de versÃ£o
            $nfeProcAtt1 = $nfeProc->appendChild($procnfe->createAttribute('versao'));
            $nfeProcAtt1->appendChild($procnfe->createTextNode($protver));
            //estabelece o atributo xmlns
            $nfeProcAtt2 = $nfeProc->appendChild($procnfe->createAttribute('xmlns'));
            $nfeProcAtt2->appendChild($procnfe->createTextNode("http://www.portalfiscal.inf.br/nfe"));
            //inclui a tag NFe
            $node = $procnfe->importNode($nodenfe, true);
            $nfeProc->appendChild($node);
            //cria tag protNFe
            $protNFe = $procnfe->createElement('protNFe');
            $nfeProc->appendChild($protNFe);
            //estabele o atributo de versÃ£o
            $protNFeAtt1 = $protNFe->appendChild($procnfe->createAttribute('versao'));
            $protNFeAtt1->appendChild($procnfe->createTextNode($versao));
            //cria tag infProt
            $nodep = $procnfe->importNode($infProt, true);
            $protNFe->appendChild($nodep);
            return $protNFe;
        }
        function getNodeValue($xml, $nodeName, $itemNum = 0, $extraTextBefore = '', $extraTextAfter = ''){
            $node = $xml->getElementsByTagName($nodeName)->item($itemNum);
            if (isset($node)) {
                $texto = html_entity_decode(trim($node->nodeValue), ENT_QUOTES, 'UTF-8');
                return $extraTextBefore . $texto . $extraTextAfter;
            }
            return '';
        }
        function getNode($xml, $nodeName, $itemNum = 0){
            $node = $xml->getElementsByTagName($nodeName)->item($itemNum);
            if (isset($node)) {
                return $node;
            }
            return '';
        }
    }
?>
