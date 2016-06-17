<?php
    require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."libs".DIRECTORY_SEPARATOR."NFe".DIRECTORY_SEPARATOR."ToolsNFePHP.class.php");
    //require_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."util".DIRECTORY_SEPARATOR."Functions.php");
    class PennaCorpNFe extends ToolsNFePHP{
        private $pennaCorpCertificate;
        private $aConfig = array();
        public function __construct(PennaCorpCertificate $pennaCorpCertificate, $modelo = 55){
            $con = con();
            $this->pennaCorpCertificate = $pennaCorpCertificate;
            $query = "select EM_RAZAO, EM_UF, EM_CGC, EM_GRUPO, EM_MODELO, EM_SERIE, ";
            $query .= "(select AF_STRING from auxfilho where ap_nome='NFE.XML' and AF_CODIGO='PRODUCAO')";
            $query .= " as AMBIENTE from empresa where em_number='".$pennaCorpCertificate->getEmpresa()."' and EM_LIGADA='T'";
            $result = mysqli_query($con, $query);
            if (mysqli_num_rows($result) == 0){
                throw new nfephpException("Empresa n�o encontrada ou n�o est� ligada");
            }
            $row = mysqli_fetch_array($result);
            if ($row['EM_GRUPO'] != "SMT"){
                throw new nfephpException("O tipo da empresa escolhida n�o � SMT, essa empresa n�o emite NFe");
            }
            $row['EM_CGC'] = preg_replace("/[^0-9]/", "", $row['EM_CGC']);
            if (strlen($row['EM_CGC']) < 14){
                throw new nfephpException("O CNPJ da empresa informada n�o � v�lido.");
            }
            $aConfigLoc = array();
            /*
                *DEFINIDO PELO nfephp
                *1-Ambiente de produ��o
                *2-Ambiente de homologa��o
            */
            $aConfigLoc['ambiente'] = ($row['AMBIENTE'] == 'PROD' ? 1 : 2);
            $aConfigLoc['empresa'] = $row['EM_RAZAO'];
            $aConfigLoc['UF'] = $row['EM_UF'];
            $aConfigLoc['cnpj'] = $row['EM_CGC'];
            $aConfigLoc['modelo'] = $row['EM_MODELO'];
            $aConfigLoc['serie'] = $row['EM_SERIE'];
            //Por seguran�a, o nome do certificado da empresa � criptografado com MD5
            $aConfigLoc['pubKey'] = $pennaCorpCertificate->getPubKey();
            $aConfigLoc['priKey'] = $pennaCorpCertificate->getPriKey();
            $aConfigLoc['certKey'] = $pennaCorpCertificate->getCertKey();
            $aConfigLoc['keyPass'] = $pennaCorpCertificate->getPassword();
            $aConfigLoc['passPhrase'] = "";
            $aConfigLoc['arquivosDir'] = __DIR__."../../../../XML/";
            $aConfigLoc['arquivoURLxml'] = "nfe_ws3_mod{$modelo}.xml";
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
        public function pcInutNF(array $parametros, array &$aRetorno = array()){
            $hasNIni = isset($parametros['nIni']);
            $hasNFim = isset($parametros['nFin']);
            if (!$hasNIni && !$hasNFim){
                throw new nfephpException("N�mero inicial e final n�o informados");
            }
            if (!$hasNIni){
                throw new nfephpException("O n�mero inicial � obrigat�rio");
            }
            //Caso n�o possua fim, mas possua in�cio, iguale o fim ao inicio
            if ($hasNIni && !$hasNFim){
                $parametros['nFin'] = $parametros['nIni'];
            }
            if (!preg_match("/^[0-9]+$/", $parametros['nIni'])){
                throw new nfephpException("O n�mero inicial � inv�lido");
            }
            if ($hasNFim && !preg_match("/^[0-9]+$/", $parametros['nFin'])){
                throw new nfephpException("O n�mero final � inv�lido");
            }
            $parametros['nIni'] = $parametros['nIni'];
            $parametros['nFin'] = $parametros['nFin'];
            $parametros['xJust'] = (isset($parametros['xJust']) ? $parametros['xJust'] : "Pedido de inutiliza��o de NFe");
            $year = date("y");
            $aConfigLoc = $this->aConfig;
            try{
                $resultado = parent::inutNF($year,
                                $aConfigLoc['serie'],
                                $parametros['nIni'],
                                $parametros['nFin'],
                                $parametros['xJust'],
                                $aConfigLoc['ambiente'],
                                $aRetorno
                );
                if (!$resultado || $this->errStatus){
                    return array("status" => false, "message" => $this->errMsg);
                }
                return array("status" => true, "message" => utf8_decode($aRetorno['xMotivo']));
            }catch(Exception $e){
                return array("status" => false, "message" => $e->getMessage());
            }
        }
        public function pcCancelEvent(array $parametros, array &$aRetorno = array()){
            extract($parametros);
            if (!isset($chNfe)){
                throw new nfephpException("A chave de acesso n�o foi informada");
            }
            if (!isset($nProt)){
                throw new nfephpException("O protocolo n�o foi informada");
            }
            if (!isset($xJust)){
                throw new nfephpException("A justificativa de cancelamento n�o foi informada");
            }
            if (!preg_match("/^[0-9]+$/", $chNfe)){
                throw new nfephpException("A chave de acesso informada � inv�lida");
            }
            if (!preg_match("/^[0-9]+$/", $nProt)){
                throw new nfephpException("O protocolo informado � inv�lido");
            }
            if (!preg_match("/^(.){15,255}$/", $xJust)){
                $error = "A justificativa de cancelamento n�o � v�lida, ";
                $error .= "ela precisa ter um tamanho entre 15 a 255 caracteres";
                throw new nfephpException($error);
            }
            $aConfigLoc = $this->aConfig;
            try{
                $resultado = parent::cancelEvent($chNfe, $nProt, $xJust, '', $aRetorno);
                if (!$resultado || $this->errStatus){
                    return array("status" => false, "message" => $this->errMsg);
                }
                return array("status" => true, "message" => $aRetorno['xMotivo']);
            }catch(Exception $e){
                return array("status" => false, "message" => $e->getMessage());
            }
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
            //estabele o atributo de versão
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
            //estabele o atributo de versão
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
