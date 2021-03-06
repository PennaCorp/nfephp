<?php
/**
 * Este arquivo � parte do projeto NFePHP - Nota Fiscal eletr�nica em PHP.
 *
 * Este programa � um software livre: voc� pode redistribuir e/ou modific�-lo
 * sob os termos da Licen�a P�blica Geral GNU (GPL)como � publicada pela Funda��o
 * para o Software Livre, na vers�o 3 da licen�a, ou qualquer vers�o posterior
 * e/ou
 * sob os termos da Licen�a P�blica Geral Menor GNU (LGPL) como � publicada pela Funda��o
 * para o Software Livre, na vers�o 3 da licen�a, ou qualquer vers�o posterior.
 *
 *
 * Este programa � distribu�do na esperan�a que ser� �til, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia expl�cita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUA��O PARA UM PROP�SITO EM PARTICULAR,
 * veja a Licen�a P�blica Geral GNU para mais detalhes.
 *
 * Voc� deve ter recebido uma c�pia da Licen�a Publica GNU e da
 * Licen�a P�blica Geral Menor GNU (LGPL) junto com este programa.
 * Caso contr�rio consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * @package   NFePHP
 * @name      ImapNFePHP
 * @version   0.2.0
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2013 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *
 */
//namespace ImapNFePHP;

class ImapNFePHP
{

    public $imaperror = '';
    
    protected $imapconn = false;
    protected $mbox = '';//{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX por exemplo
    protected $host = '';//imap.gmail.com por exemplo
    protected $user = '';//seumnome@gmail.com por exemplo
    protected $pass = '';//passW0rd por exemplo
    protected $port = '';//143, 993
    protected $protocol = ''; //imap, pop3, nntp
    protected $security = ''; //tls, notls, ssl
    protected $validcerts = ''; //validate-cert, novalidate-cert
    protected $imapfolder = 'INBOX'; //INBOX
    protected $downfolder = '';//../exemplos
    protected $limitmsg = 10;//limite de mensagem a serem processadas de cada vez
    protected $filesulfix = 'xml';//sulfixo do arquivo anexado que desejamos baixar
    protected $imapaction = 'none'; //none, delele ou move
    protected $imapnewfolder = ''; //essa pasta j� deve existir na caixa postal
    protected $processedmsgs = array(); //lista de mensagens processadas e os dados do processamento
    
    private $imapchange = false;// marca indica se houve modifica��es na caixa postal a serem atualizadas
    private $imapmod = false; //indica se o modulo imap est� ativado no php
    
    /**
     * Construtor da classe
     * @param boolean $debug ativa o debug do php
     */
    public function __construct($debug = false)
    {
        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        $this->checkImapModule();
        
    }//fim construct
    
    /**
     * destrutor da classe
     */
    public function __destruct()
    {
        $this->imapDisconnect();
    }
    
    private function checkImapModule()
    {
        if (!extension_loaded('imap')) {
            $this->imapmod = false;
            $this->imaperror = 'Modulo IMAP n�o est� carregado no PHP';
        } else {
            $this->imapmod = true;
        }
    }
    
    //parametros
    public function setHost($str)
    {
        if ($str != '') {
            $this->host = $str;
        }
    }
    
    public function getHost()
    {
        return $this->host;
    }

    public function setPort($str)
    {
        if ($str != '') {
            $this->port = $str;
        }
    }
    
    public function getPort()
    {
        return $this->port;
    }

    public function setUser($str)
    {
        if ($str != '') {
            $this->user = $str;
        }
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setPass($str)
    {
        if ($str != '') {
            $this->pass = $str;
        }
    }
    
    public function getPass()
    {
        return $this->pass;
    }
   
    public function setProtocol($str)
    {
        if ($str != '') {
            if ($str == 'imap' || $str == 'pop3' || $str == 'nntp') {
                $this->protocol = $str;
            } else {
                $this->protocol = '';
            }
        }
    }
    
    public function getProtocol()
    {
        return $this->protocol;
    }

    public function setSecurity($str)
    {
        if ($str != '') {
            if ($str == 'ssl' || $str == 'tls' || $str == 'notls') {
                $this->security = $str;
            } else {
                $this->security = '';
            }
        }
    }
    
    public function getSecurity()
    {
        return $this->security;
    }
    
    public function setValidCerts($str)
    {
        if ($str != '') {
            if ($str == 'novalidate-cert' || $str == 'validate-cert') {
                $this->validcerts = $str;
            } else {
                $this->validcerts = '';
            }
        }
    }
    
    public function getValidCerts()
    {
        return $this->validcerts;
    }
    
    public function setImapFolder($str)
    {
        if ($str != '') {
            $this->imapfolder = $str;
        }
    }
    
    public function getImapFolder()
    {
        return $this->imapfolder;
    }
    
    public function setDownFolder($str)
    {
        if ($str != '') {
            if (is_dir($str)) {
                $this->downfolder = $str;
            }
        }
    }

    public function getDownFolder()
    {
        return $this->downfolder;
    }
 
    public function setLimitMsgs($str)
    {
        if ($str != '') {
            if (is_numeric($str)) {
                $this->limitmsg = $str;
            }
        }
    }
    
    public function getLimitMsgs()
    {
        return $this->limitmsg;
    }
    
    public function setFileSulfix($str)
    {
        if ($str != '') {
            $this->filesulfix = $str;
        }
    }
    
    public function getFileSulfix()
    {
        return $this->filesulfix;
    }
    
    public function setImapAction($str)
    {
        if ($str == 'delete' || $str == 'move' || $str == 'none') {
            $this->imapaction = $str;
        } else {
            $this->imapaction = 'none';
        }
    }
    
    public function getImapAction()
    {
        return $this->imapaction;
    }
    
    public function setImapNewFolder($str)
    {
        $this->imapnewfolder = $str;
    }
    
    public function getImapNewFolder()
    {
        return $this->imapnewfolder;
    }
    
    public function getMbox()
    {
        return $this->mbox;
    }
    
    public function getImapError()
    {
        return $this->imaperror;
    }
    
    public function getProcessedMsgs()
    {
        return $this->processedmsgs;
    }
    
    /**
     * Monta express�o para conex�o imap
     */
    protected function mboxExpression()
    {
        if ($this->imapmod && $this->host != ''
                && $this->port != ''
                && $this->imapfolder != ''
                && $this->downfolder != '') {
            $tProtocol = ($this->protocol != '') ? '/'. $this->protocol : '';
            $tSecurity = ($this->security != '') ? '/'. $this->security : '';
            $tValidcerts = ($this->validcerts != '') ? '/'. $this->validcerts : '';
            $this->mbox = "{".$this->host.":".$this->port.$tProtocol.$tSecurity.$tValidcerts."}".$this->imapfolder;
        } else {
            $this->mbox = '';
        }
    }
    
    /**
     * Estabelece conex�o com servidor IMAP
     * 
     * @param string $config array para configura��o
     * @return boolean true sucesso ou false fracasso, nesse caso consulte a vari�vel imaperror
     */
    public function imapConnect($config = '')
    {
        if ($this->imapconn !== false) {
            return true;
        }
        if (is_array($config)) {
            $this->makeConfig($config);
        }
        $this->mboxExpression();
        if ($this->mbox != '') {
            $this->imapconn = imap_open($this->mbox, $this->user, $this->pass);
            if ($this->imapconn !== false) {
                //sucesso
                return true;
            } else {
                //fracasso
                $this->imaperror .= imap_last_error();
                return false;
            }
        } else {
            return false;
        }
    }//fim connect

    private function makeConfig($config)
    {
        $this->setHost($config['host']);
        $this->setUser($config['user']);
        $this->setPass($config['pass']);
        $this->setPort($config['port']);
        $this->setProtocol($config['protocol']);
        $this->setSecurity($config['security']);
        $this->setValidCerts($config['validcerts']);
        $this->setImapFolder($config['imapfolder']);
        $this->setDownFolder($config['downfolder']);
        $this->setFileSulfix($config['filesulfix']);
        $this->setImapAction($config['action']);
        $this->setImapNewFolder($config['newfolder']);
        $this->mboxExpression();
    }
    
    /**
     * Finaliza a comunica��o IMAP anteriormente iniciada, se houver
     */
    public function imapDisconnect()
    {
        if ($this->imapconn != false) {
            if ($this->imapchange) {
                imap_expunge($this->imapconn);
            }
            imap_close($this->imapconn);
            $this->imapconn = false;
        }
    }
    
    /**
     * Busca por toda a pasta imap da caixa de correio por mensagens contendo arquivos xml
     * anexados, caso existam estes ser�o baixados para a pasta de download indicada.
     * Todas as mensagens ser�o removidas da pasta da caixa postal, 
     * aquelas sem anexos em xml imeditamente e as com anexos xml ap�s os mesmos serem 
     * baixados com sucesso
     * @return boolean
     */
    public function imapGetXmlFiles()
    {
        $response = array();
        if ($this->imapConnect()) {
            $qtd = @imap_num_msg($this->imapconn);
            if ($qtd > $this->limitmsg) {
                $max = $this->limitmsg;
            } else {
                $max = $qtd;
            }
            for ($nMsg = 1; $nMsg <= $max; $nMsg++) {
                //verificar cada mensagem por anexos
                $uid = @imap_uid($this->imapconn, $nMsg);
                $aResults = @imap_fetch_overview($this->imapconn, $uid, FT_UID);
                foreach ($aResults as $message) {
                    $msgno = $message->msgno;
                    $actionmark = $this->downFile($msgno, $aAtt);
                    $response[$nMsg-1]['actionmark'] = $actionmark;
                    $response[$nMsg-1]['action'] = $this->imapaction;
                    $response[$nMsg-1]['from'] = $message->from;
                    $response[$nMsg-1]['subject'] = $message->subject;
                    $response[$nMsg-1]['date'] = $message->date;
                    $response[$nMsg-1]['attachments'] = $aAtt;
                    if ($actionmark) {
                        $success = $this->imapAction($msgno, $message->uid);
                    }
                    $response[$nMsg-1]['success'] = $success;
                }//fim foreach message
            }//fim for $qtd
        }//fim imapConnect
        if (isset($response)) {
            $this->processedmsgs = $response;
        }
        return true;
    }//fim imapGet
    
    private function imapAction($msgno, $uid)
    {
        $success = true;
        switch ($this->imapaction) {
            case 'delete':
                if (imap_delete($this->imapconn, $uid, FT_UID)) {
                    $this->imapchange = true;
                } else {
                    $this->imaperror .= imap_last_error();
                    $success = false;
                }
                break;
            case 'move':
                if (imap_mail_move($this->imapconn, "$msgno:$msgno", $this->imapnewfolder)) {
                    $this->imapchange = true;
                } else {
                    $this->imaperror .= imap_last_error();
                    $success = false;
                }
                break;
            case 'none':
                break;
            default:
                break;
        }
        return $success;
    }
    
    /**
     * Executa o download propriamente dito do arquivo anexado ao email
     * @param string $msgno numero da mensagem
     * @param array $aAtt array com os dados dos anexos e dos resultados do download
     * @return boolean $delete indica se a mensagem deve ser deixada ou (movida ou deletada)
     */
    private function downFile($msgno, &$aAtt)
    {
        $aArqs = $this->imapAttachments($this->imapconn, $msgno);
        $actionmark = true;
        $iCount = 0;
        foreach ($aArqs as $arq) {
            if ($arq['is_attachment'] == false) {
                //n�o tem anexo ent�o marcar para a��o
                continue; //foreach $arq
            }
            $attachname = strtolower($arq['filename']);
            if (!$this->fileSulfixCompare($attachname, $this->filesulfix)) {
                //tem anexo mas n�o tem o sulfixo indicado, ent�o marcar para a��o
                $aAtt[$iCount]['attachname'] = $attachname;
                $aAtt[$iCount]['download'] = false;
                $iCount++;
                continue; //foreach $arq
            }
            $filename = date('Ymd').$msgno.str_replace(' ', '_', $attachname);
            //$content = str_replace(array("\n","\r","\t"), "", $arq['attachment']);
            $aAtt[$iCount]['attachname'] = $attachname;
            $content = $arq['attachment'];
            $fileH = fopen($this->downfolder.DIRECTORY_SEPARATOR.$filename, "w");
            if (fwrite($fileH, $content)) {
                fclose($fileH);
                @chmod($this->downfolder.DIRECTORY_SEPARATOR.$filename, 0755);
                //arquivo salvo com sucesso, ent�o marcar para a��o
                $aAtt[$iCount]['download'] = true;
                $iCount++;
            } else {
                //como n�o foi possivel fazer o download manter o email
                $aAtt[$iCount]['download'] = false;
                $this->imaperror .= 'Falha ao tentar gravar o aquivo.';
                $actionmark = false;
            }
        }//fim foreach $arq
        if (!isset($aAtt)) {
            $aAtt = '';
        }
        return $actionmark;
    }
    
    private function fileSulfixCompare($filename, $filesulfix)
    {
        if ($filesulfix == '') {
            return false;
        }
        if ($filesulfix == '*') {
            return true;
        }
        if (is_array($filesulfix)) {
            $aSulf = $filesulfix;
        } else {
            $aSulf = array($filesulfix);
        }
        foreach ($aSulf as $sulfix) {
            $num = (-1 * strlen($sulfix));
            if (substr($filename, $num) == strtolower($sulfix)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Recupera todos os anexos da mensagem
     * @param object $connection
     * @param string $messageNumber
     * @return array com os dados dos anexos
     */
    protected function imapAttachments($connection, $messageNumber)
    {
        $attachments = array();
        $structure = imap_fetchstructure($connection, $messageNumber);
        if (isset($structure->parts) && count($structure->parts)) {
            for ($iCount = 0; $iCount < count($structure->parts); $iCount++) {
                $attachments[$iCount] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );
                if ($structure->parts[$iCount]->ifdparameters) {
                    foreach ($structure->parts[$iCount]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachments[$iCount]['is_attachment'] = true;
                            $attachments[$iCount]['filename'] = $object->value;
                        }
                    }
                }
                if ($structure->parts[$iCount]->ifparameters) {
                    foreach ($structure->parts[$iCount]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$iCount]['is_attachment'] = true;
                            $attachments[$iCount]['name'] = $object->value;
                        }
                    }
                }
                if ($attachments[$iCount]['is_attachment']) {
                    $attachments[$iCount]['attachment'] = imap_fetchbody($connection, $messageNumber, $iCount+1);
                    if ($structure->parts[$iCount]->encoding == 3) { // 3 = BASE64
                        $attachments[$iCount]['attachment'] = base64_decode($attachments[$iCount]['attachment']);
                    } elseif ($structure->parts[$iCount]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                        $attachments[$iCount]['attachment'] = quoted_printable_decode(
                            $attachments[$iCount]['attachment']
                        );
                    }
                }
            }//fim for
        }
        return $attachments;
    }//fim
}//fim classe
