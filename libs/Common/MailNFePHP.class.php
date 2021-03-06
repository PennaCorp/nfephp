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
 *
 * @package   NFePHP
 * @name      MailNFePHP
 * @version   2.2.15
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2012 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux dot rlm at gmail dot com>
 *
 *          CONTRIBUIDORES (em ordem alfabetica):
 *
 *              Cristiano Soares <soares dot cr at gmail dot com>
 *              Elton Nagai <eltaum at gmail dot com>
 *              Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *              Lucas Vaccaro <lucas-vaccaro at outlook dot com>
 *              Jo�o Eduardo Silva Corr�a <jscorrea2 at gmail dot com>
 *              Rodrigo W Cardoso <rodrigogepem at gmail dot com>
 *
 * Esta classe presume que ser� usada a mesma conta de email para o envio e recebimento das NFe
 */
//define o caminho base da instala��o do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);
}
//carrega as classes do PHPMailer
require_once PATH_ROOT.'libs/External/PHPMailer/class.phpmailer.php';

class MailNFePHP
{
    public $mailAuth='1';
    public $mailFROM='';
    public $mailHOST='';
    public $mailUSER='';
    public $mailPASS='';
    public $mailPORT='';
    public $mailPROTOCOL='';
    public $mailFROMmail='';
    public $mailFROMname='';
    public $mailREPLYTOmail='';
    public $mailREPLYTOname='';
    public $mailERROR='';
    public $mailIMAPhost = '';
    public $mailIMAPport = '143';
    public $mailIMAPsecurity = 'tls';
    public $mailIMAPnocerts = 'novalidate-cert';
    public $mailIMAPbox = 'INBOX';
    public $recebidasDir ='';
    public $temporariasDir='';
    public $canceladasDir='';
    public $CNPJ='';

    protected $debugMode = 0;
    /**
     * "Layout Template" do corpo do email em html
     * Os dados vari�veis da mensagem html s�o :
     * {numero}{serie}{emitente}{valor}{status}
     * esses campos ser�o substituidos durante o envio do email
     * @var string
     */
    protected $layouthtml = '';

    /**
     * __contruct
     * Construtor da classe MailNFePHP
     * @param array $aConfig Matriz com os dados de configura��o
     * @param number $mododebug (Optional) 1-SIM ou 0-N�O (0 default)
     * @package NFePHP
     * @author  Roberto L. Machado <linux dot rlm at gmail dot com>
     */
    public function __construct($aConfig = '', $mododebug = 0)
    {
        if (is_numeric($mododebug)) {
            $this->debugMode = $mododebug;
        }
        if ($this->debugMode) {
            //ativar modo debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            //desativar modo debug
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        //verifica a existencia do layout alternativo
        if (is_file('../config/layout_email.html')) {
            $this->layouthtml = file_get_contents('../config/layout_email.html');
        }
        $this->mailERROR='';
        if (is_array($aConfig)) {
            $this->mailAuth  = $aConfig['mailAuth'];
            $this->mailFROM  = $aConfig['mailFROM'];
            $this->mailHOST = $aConfig['mailHOST'];
            $this->mailUSER  = $aConfig['mailUSER'];
            $this->mailPASS  = $aConfig['mailPASS'];
            $this->mailPORT  = $aConfig['mailPORT'];
            $this->mailPROTOCOL  = $aConfig['mailPROTOCOL'];
            $this->mailFROMmail  = $aConfig['mailFROMmail'];
            $this->mailFROMname  = $aConfig['mailFROMname'];
            $this->mailREPLYTOmail = $aConfig['mailREPLYTOmail'];
            $this->mailREPLYTOname = $aConfig['mailREPLYTOname'];
            $this->mailIMAPhost = $aConfig['mailIMAPhost'];
            $this->mailIMAPport = $aConfig['mailIMAPport'];
            $this->mailIMAPsecurity = $aConfig['mailIMAPsecurity'];
            $this->mailIMAPnocerts = $aConfig['mailIMAPnocerts'];
            $this->mailIMAPbox = $aConfig['mailIMAPbox'];
            $this->recebidasDir = $aConfig['recebidasDir'];
            $this->temporariasDir = $aConfig['temporariasDir'];
            $this->canceladasDir = $aConfig['canceladasDir'];
            $this->CNPJ = $aConfig['cnpj'];
            if ($aConfig['mailLayoutFile'] != '') {
                if (is_file(PATH_ROOT.'config/'.$aConfig['mailLayoutFile'])) {
                    $this->layouthtml = file_get_contents(PATH_ROOT.'config/'.$aConfig['mailLayoutFile']);
                }
            }
        } else {
            if (is_file(PATH_ROOT.'config/config.php')) {
                include(PATH_ROOT.'config/config.php');
                $this->mailAuth  = $mailAuth;
                $this->mailFROM  = $mailFROM;
                $this->mailHOST = $mailHOST;
                $this->mailUSER  = $mailUSER;
                $this->mailPASS  = $mailPASS;
                $this->mailPORT  = $mailPORT;
                $this->mailPROTOCOL  = $mailPROTOCOL;
                $this->mailFROMmail  = $mailFROMmail;
                $this->mailFROMname  = $mailFROMname;
                $this->mailREPLYTOmail = $mailREPLYTOmail;
                $this->mailREPLYTOname = $mailREPLYTOname;
                $this->mailIMAPhost = $mailIMAPhost;
                $this->mailIMAPport = $mailIMAPport;
                $this->mailIMAPsecurity = $mailIMAPsecurity;
                $this->mailIMAPnocerts = $mailIMAPnocerts;
                $this->mailIMAPbox = $mailIMAPbox;
                if ($ambiente == 1) {
                    $caminho = DIRECTORY_SEPARATOR . 'producao';
                } else {
                    $caminho = DIRECTORY_SEPARATOR . 'homologacao';
                }
                $this->recebidasDir = $arquivosDir.$caminho.DIRECTORY_SEPARATOR.'recebidas'.DIRECTORY_SEPARATOR;
                $this->temporariasDir = $arquivosDir.DIRECTORY_SEPARATOR.$caminho.DIRECTORY_SEPARATOR.
                        'temporarias'.DIRECTORY_SEPARATOR;
                $this->canceladasDir = $arquivosDir.$caminho.DIRECTORY_SEPARATOR.'canceladas'.DIRECTORY_SEPARATOR;
                $this->CNPJ = $cnpj;
                if ($mailLayoutFile != '') {
                    if (is_file(PATH_ROOT.'config/'.$mailLayoutFile)) {
                        $this->layouthtml = file_get_contents(PATH_ROOT.'config/'.$mailLayoutFile);
                    }
                }
            }
        }
    } // end__construct

    /**
     * enviaMail
     * Fun��o de envio de emails da NFe a partir dos endere�os de email inclusos no pr�prio xml
     *
     * @name    enviaMail
     * @param   string $filename passar uma string com o caminho completo para o arquivo XML
     * @param   string $para For�a o envio da comunica��o apenas para o email indicado
     * @return  boolean TRUE sucesso ou FALSE falha
     */
    public function enviaMail($filename = '', $sendto = '')
    {
        if (is_file($filename)) {
            $retorno = true;
            //quebra o path em um array usando o separador / ou \
            $aFile = explode(DIRECTORY_SEPARATOR, $filename);
            $nomeXML = $aFile[count($aFile)-1];
            $docXML = file_get_contents($filename);
            $dom = new DomDocument;
            $dom->loadXML($docXML);
            $nfeProc    = $dom->getElementsByTagName("nfeProc")->item(0);
            $infNFe     = $dom->getElementsByTagName("infNFe")->item(0);
            $ide        = $dom->getElementsByTagName("ide")->item(0);
            $emit       = $dom->getElementsByTagName("emit")->item(0);
            $dest       = $dom->getElementsByTagName("dest")->item(0);
            $obsCont    = $dom->getElementsByTagName("obsCont");
            $ICMSTot    = $dom->getElementsByTagName("ICMSTot")->item(0);
            $razao      = utf8_decode($dest->getElementsByTagName("xNome")->item(0)->nodeValue);
            $numero     = str_pad($ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
            $serie      = str_pad($ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
            $emitente   = utf8_decode($emit->getElementsByTagName("xNome")->item(0)->nodeValue);
            $vtotal     = number_format($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".");
            $cStat      = $nfeProc->getElementsByTagName("cStat")->item(0)->nodeValue;
            $chave      = str_replace('NFe', '', $infNFe->getAttribute("Id"));
            if ($cStat == '100' || $cStat == '101' || $cStat == '135' || $cStat == '136') {
                if ($sendto == '') {
                    //buscar emails
                    $emailaddress = !empty($dest->getElementsByTagName("email")->item(0)->nodeValue) ?
                        utf8_decode($dest->getElementsByTagName("email")->item(0)->nodeValue) :
                        '';
                    if (strtoupper(trim($emailaddress)) == 'N/D' || strtoupper(trim($emailaddress)) == '') {
                        $emailaddress = '';
                    } else {
                        $emailaddress = trim($emailaddress);
                        $emailaddress = str_replace(';', ',', $emailaddress);
                        $emailaddress = str_replace(':', ',', $emailaddress);
                        $emailaddress = str_replace('/', ',', $emailaddress);
                        $email = explode(',', $emailaddress);
                    }
                    if (isset($obsCont)) {
                        $i = 0;
                        foreach ($obsCont as $obs) {
                            $campo =  $obsCont->item($i)->getAttribute("xCampo");
                            $xTexto = !empty($obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue) ?
                                $obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue :
                                '';
                            if (substr($campo, 0, 5) == 'email' && $xTexto != '') {
                                $xTexto = str_replace(';', ',', $xTexto);
                                $xTexto = str_replace(':', ',', $xTexto);
                                $xTexto = str_replace('/', ',', $xTexto);
                                $aTexto = explode(',', $xTexto);
                                foreach ($aTexto as $t) {
                                    $email[] = $t;
                                }
                            } //fim if
                            $i++;
                        } //foreach($obsCont
                    }//fim if (isset($obsCont))
                } else {
                    $email[] = $sendto;
                }
                $aMail['contato'] = '';
                $aMail['razao'] = $razao;
                $aMail['numero'] = $numero;
                $aMail['serie'] = $serie;
                $aMail['emitente'] = $emitente;
                $aMail['vtotal'] = $vtotal;
                $aMail['cStat'] = $cStat;
                //para cada endere�o de email encontrado na NFe
                foreach ($email as $mail) {
                    $aMail['para'] = $mail;
                    if (!$this->sendNFe($docXML, '', $nomeXML, '', $aMail, '1')) {
                        $this->mailERROR .= 'Falha ao enviar para '.$mail.'!! ';
                        $retorno = false;
                    }
                } //fim foreach
            } //fim if(is_file(
        } else {
            $this->mailERROR .= 'Essa nota fiscal n�o est� autorizada n.'. $numero . ' / ' . $serie.'!!';
            $retorno = false;
        }//if cStat
        return $retorno;
    }//fim enviaMail

    /**
     * sendNFe
     * Fun��o para envio da NF-e por email usando PHPMailer
     *
     * @package NFePHP
     * @name sendNFe
     * @param string $docXML arquivo XML, � obrigat�rio
     * @param string $docPDF DANFE em formato PDF, se n�o quizer mandar o pdf deixe em branco
     * @param string $nomeXML Nome do arquivo XML, � obrigat�rio
     * @param string $nomePDF Nome do arquivo PDF
     * @param array $aMail Matriz com as informa��es necess�rias para envio do email
     * @param boolean $auth Indica se � necess�ria a autentica��o
     * @return boolean TRUE sucesso ou FALSE falha
     */
    public function sendNFe($docXML = '', $docPDF = '', $nomeXML = '', $nomePDF = '', $aMail = '', $auth = '')
    {
        //se n�o forem passados os parametros de envio sair
        if (!is_array($aMail)) {
            $this->mailERROR = 'N�o foram passados parametros de envio!';
            return false;
        }
        //retorna se n�o foi passado o xml
        if ($docXML != '' && $nomeXML != '') {
            $fileXML = $this->temporariasDir.$nomeXML;
            //retorna false se houve erro na grava��o
            if (!file_put_contents($fileXML, $docXML)) {
                $this->mailERROR = '' .
                "N�o foi possivel gravar o XML para envio. Permiss�o Negada ao tentar gravar $fileXML!";
                return false;
            }
        } else {
            $this->mailERROR = 'N�o foi passados o XML da NFe para envio. O XML � Obrigat�rio!';
            return false;
        }
        //validar o endere�o de email passado
        if (!$this->validEmailAdd($aMail['para'])) {
            $this->mailERROR .= 'O endere�o informado n�o � valido! '.$aMail['para'];
            return false;
        }
        if ($auth == '') {
            if (isset($this->mailAuth)) {
                $auth = $this->mailAuth;
            } else {
                $auth = '1';
            }
        }
        $to = $aMail['para'];
        $contato = $aMail['contato'];
        $razao = $aMail['razao'];
        $numero = $aMail['numero'];
        $serie = $aMail['serie'];
        $emitente = $aMail['emitente'];
        $valor = $aMail['vtotal'];
        $cStat = $aMail['cStat'];
        if ($contato=='' || $contato == $razao) {
            $contato = $razao;
        } else {
            $contato .= ' - '.$razao;
        }
        //se n�o foi passado o pdf ignorar e s� enviar o xml
        if ($docPDF != '' && $nomePDF != '') {
            //salvar temporariamente os arquivo passados
            $filePDF = $this->temporariasDir.'pdf-'. number_format(microtime(true)*1000000, 0, 0, 15) .'.pdf';
            file_put_contents($filePDF, $docPDF);
        } else {
            $filePDF = '';
        }
        if ($cStat == '101' || $cStat == '135' || $cStat == '136') {
            $chave = substr($nomeXML, 0, 44);
            $extra = $this->canceladasDir . $chave . '-1-procCanc.xml';
            //mensagem no corpo do email em html
            if ($this->layoutCanchtml != '') {
                $msg = $this->layoutCanchtml;
            } else {
                $msg = "<p><b>Prezado Sr(a) {contato},</b><h2>{status}</h2></p>";
                $msg .= "<p>Voc� est� recebendo a notifica��o de Cancelamento";
                $msg .= " da Nota Fiscal Eletr�nica n�mero {numero}, s�rie {serie} de {emitente}</p>";
                $msg .= "<p><i>Podemos conceituar a Nota Fiscal Eletr�nica como um documento de exist�ncia";
                $msg .= " apenas digital, emitido e armazenado eletronicamente, com o intuito de documentar,";
                $msg .= " para fins fiscais, uma opera��o de circula��o de mercadorias, ocorrida entre as partes.";
                $msg .= " Sua validade jur�dica � garantida pela assinatura digital do remetente";
                $msg .= " (garantia de autoria e de integridade) e recep��o, pelo Fisco, do documento eletr�nico,";
                $msg .= " antes da ocorr�ncia do Fato Gerador.</i></p>";
                $msg .= "<p><i>Os registros fiscais e cont�beis devem ser feitos, a partir do pr�prio";
                $msg .= " arquivo da NF-e e do Cancelamento, anexo neste e-mail. A validade e autenticidade";
                $msg .= " deste documento eletr�nico pode ser verificada no site nacional do projeto";
                $msg .= " (www.nfe.fazenda.gov.br), atrav�s da chave de acesso contida no DANFE.</i></p>";
                $msg .= "<p><i></i></p>";
                $msg .= "<p><b></b></p>";
                $msg .= "<p>Para mais detalhes sobre o projeto, consulte: ";
                $msg .= "<a href='http://www.nfe.fazenda.gov.br/portal/Default.aspx'>www.nfe.fazenda.gov.br</a></p>";
                $msg .= "<br /><p>Atenciosamente,<p>{emitente}</p>";
            }
            // assunto email
            $subject = utf8_decode("Cancelamento de NF-e Nota Fiscal Eletr�nica - N.$numero - $emitente");
            // substitui campos vari�veis
            $msg = str_replace('{status}', ' Cancelamento ', $msg);
        } else {
            $extra = '';
            //mensagem no corpo do email em html
            if ($this->layouthtml != '') {
                $msg = $this->layouthtml;
            } else {
                $msg = "<p><b>Prezado Sr(a) {contato},</b><h2>{status}</h2></p>";
                $msg .= "<p>Voc� est� recebendo a Nota Fiscal Eletr�nica n�mero {numero}, s�rie {serie} de";
                $msg .= " {emitente}, no valor de R$ {valor}. Junto com a mercadoria, voc� receber� tamb�m um DANFE";
                $msg .= " (Documento Auxiliar da Nota Fiscal Eletr�nica), que acompanha o tr�nsito das";
                $msg .= " mercadorias.</p>";
                $msg .= "<p><i>Podemos conceituar a Nota Fiscal Eletr�nica como um documento de exist�ncia apenas";
                $msg .= " digital, emitido e armazenado eletronicamente, com o intuito de documentar, para fins";
                $msg .= " fiscais, uma opera��o de circula��o de mercadorias, ocorrida entre as partes.";
                $msg .= " Sua validade jur�dica garantida pela assinatura digital do remetente (garantia de autoria";
                $msg .= " e de integridade) e recep��o, pelo Fisco, do documento eletr�nico, antes da";
                $msg .= " ocorr�ncia do Fato Gerador.</i></p>";
                $msg .= "<p><i>Os registros fiscais e cont�beis devem ser feitos, a partir do pr�prio arquivo";
                $msg .= " da NF-e, anexo neste e-mail, ou utilizando o DANFE, que representa graficamente a Nota";
                $msg .= " Fiscal Eletr�nica. A validade e autenticidade deste documento eletr�nico pode ser";
                $msg .= " verificada no site nacional do projeto (www.nfe.fazenda.gov.br), atrav�s da chave de acesso";
                $msg .= " contida no DANFE.</i></p>";
                $msg .= "<p><i>Para poder utilizar os dados descritos do DANFE na escritura��o da NF-e, tanto o";
                $msg .= " contribuinte destinat�rio, como o contribuinte emitente, ter�o de verificar a validade da";
                $msg .= " NF-e. Esta validade est� vinculada � efetiva exist�ncia da NF-e nos arquivos da SEFAZ,";
                $msg .= " e comprovada atrav�s da emiss�o da Autoriza��o de Uso.</i></p>";
                $msg .= "<p><b>O DANFE n�o � uma nota fiscal, nem substitui uma nota fiscal, servindo apenas";
                $msg .= " como instrumento auxiliar para consulta da NF-e no Ambiente Nacional.</b></p>";
                $msg .= "<p>Para mais detalhes sobre o projeto, consulte: ";
                $msg .= "<a href='http://www.nfe.fazenda.gov.br/portal/Default.aspx'>www.nfe.fazenda.gov.br</a></p>";
                $msg .= "<br /><p>Atenciosamente,<p>{emitente}</p>";
            }
            // assunto email
            $subject = utf8_decode("NF-e Nota Fiscal Eletr�nica - N.$numero - $emitente");
            // substitui campos vari�veis
            $msg = str_replace('{status}', 'Autoriza��o', $msg);
            $msg = str_replace('{valor}', $valor, $msg);
        }
        // substitui campos vari�veis
        $msg = str_replace('{contato}', $contato, $msg);
        $msg = str_replace('{emitente}', $emitente, $msg);
        $msg = str_replace('{numero}', $numero, $msg);
        $msg = str_replace('{serie}', $serie, $msg);
        //corrige de utf8 para iso
        $msg = utf8_decode($msg);
        $txt = $this->html2txt($msg);
        // O email ser� enviado no formato HTML
        $htmlMessage = "<body bgcolor='#ffffff'>$msg</body>";
        //enviar o email
        if (!$result = $this->sendM($to, $contato, $subject, $txt, $htmlMessage, $fileXML, $filePDF, $auth, $extra)) {
            //houve falha no envio reportar
            $this->mailERROR = 'Falha no envio do email: ' . $this->mailERROR;
        }
        //apagar os arquivos salvos temporariamente
        if (is_file($fileXML)) {
            unlink($fileXML);
        }
        if (is_file($filePDF)) {
            unlink($filePDF);
        }
        return $result; //retorno da fun��o
    } //fim da fun��o sendNFe


    /**
     * __sendM
     * Fun��o de envio do email
     *
     * @name sendM
     * @param string $to            endere�o de email do destinat�rio
     * @param string $contato       Nome do contato - empresa
     * @param string $subject       Assunto
     * @param string $txt           Corpo do email em txt
     * @param string $htmlMessage   Corpo do email em html
     * @param string $fileXML       path completo para o arquivo xml
     * @param string $filePDF       path completo para o arquivo pdf
     * @param string $auth          Flag da autoriza��o requerida 1-Sim 0-N�o
     * @return boolean FALSE em caso de erro e TRUE se sucesso
     */
    private function sendM($to, $contato, $subject, $txt, $htmlMessage, $fileXML, $filePDF, $auth, $extra = '')
    {
        // o parametro true indica que uma exce��o ser� criada em caso de erro,
        $mail = new PHPMailer(true);
        // informa a classe para usar SMTP
        $mail->IsSMTP();
        // executa a��es
        try {
            $mail->Host = $this->mailHOST;        // SMTP server
            $mail->SMTPDebug = 0;                      // habilita debug SMTP para testes
            $mail->Port = $this->mailPORT;        // Seta a porta a ser usada pelo SMTP
            if ($auth=='1' && $this->mailUSER != '' && $this->mailPASS !='') {
                $mail->SMTPAuth   = true;                   // habilita autientica��o SMTP
                if ($this->mailPROTOCOL !='') {
                    $mail->SMTPSecure = $this->mailPROTOCOL;    // "tls" ou "ssl"
                }
                $mail->Username = $this->mailUSER;        // Nome do usu�rios do SMTP
                $mail->Password = $this->mailPASS;        // Password do usu�rio SMPT
            } else {
                $mail->SMTPAuth = false;
            }
            $mail->AddReplyTo($this->mailREPLYTOmail, $this->mailREPLYTOname); //Indica��o do email de retorno
            $mail->AddAddress($to, $contato);            // nome do destinat�rio
            $mail->SetFrom($this->mailFROMmail, $this->mailFROMname); //identifica��o do emitente
            $mail->Subject = $subject;                  // Assunto
            $mail->AltBody = $txt;                      // Corpo a mensagem em txt
            $mail->MsgHTML($htmlMessage);               // Corpo da mensagem em HTML
            if (is_file($fileXML)) {
                $mail->AddAttachment($fileXML);          // Anexo
            }
            if (is_file($filePDF)) {
                $mail->AddAttachment($filePDF);          // Anexo
            }
            if (is_file($extra)) {
                $mail->AddAttachment($extra);          // Anexo
            }
            $mail->Send();                              // Comando de envio
            $result = true;
            // � necess�rio buscar o erro
        } catch (phpmailerException $e) {               // captura de erros
            $this->mailERROR .= $e->errorMessage();      //Mensagens de erro do PHPMailer
            $result = false;
        } catch (Exception $e) {
            $this->mailERROR .=  $e->getMessage();      //Mensagens de erro por outros motivos
            $result = false;
        }
        return $result;
    } //fim sendM

    /**
     * validEmailAdd
     * Fun��o de valida��o dos endere�os de email
     *
     * @name validEmailAdd
     * @version 1.02
     * @author  Douglas Lovell <http://www.linuxjournal.com/article/9585>
     * @param string $email Endere�o de email a ser testado, podem ser passados v�rios
     *                      endere�os separados por virgula
     * @return boolean True se endere�o � verdadeiro ou false caso haja algum erro
     */
    public function validEmailAdd($email)
    {
        $isValid = true;
        $aMails = explode(',', $email);
        foreach ($aMails as $email) {
            $atIndex = strrpos($email, "@");
            if (is_bool($atIndex) && !$atIndex) {
                $this->mailERROR .= "$email - Isso n�o � um endere�o de email.\n";
                $isValid = false;
            } else {
                $domain = substr($email, $atIndex+1);
                $local = substr($email, 0, $atIndex);
                $localLen = strlen($local);
                $domainLen = strlen($domain);
                if ($localLen < 1 || $localLen > 64) {
                    // o endere�o local � muito longo
                    $this->mailERROR .= "$email - O endere�o � muito longo.\n";
                    $isValid = false;
                } elseif ($domainLen < 1 || $domainLen > 255) {
                    // o comprimento da parte do dominio � muito longa
                    $this->mailERROR .= "$email - O comprimento do dominio � muito longo.\n";
                    $isValid = false;
                } elseif ($local[0] == '.' || $local[$localLen-1] == '.') {
                    // endere�o local inicia ou termina com ponto
                    $this->mailERROR .= "$email - Parte do endere�o inicia ou termina com ponto.\n";
                    $isValid = false;
                } elseif (preg_match('/\\.\\./', $local)) {
                    // endere�o local com dois pontos consecutivos
                    $this->mailERROR .= "$email - Parte do endere�o tem dois pontos consecutivos.\n";
                    $isValid = false;
                } elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                    // caracter n�o valido na parte do dominio
                    $this->mailERROR .= "$email - Caracter n�o v�lido na parte do dom�nio.\n";
                    $isValid = false;
                } elseif (preg_match('/\\.\\./', $domain)) {
                    // parte do dominio tem dois pontos consecutivos
                    $this->mailERROR .= "$email - Parte do dom�nio tem dois pontos consecutivos.\n";
                    $isValid = false;
                } elseif (!preg_match(
                    '/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                    str_replace("\\\\", "", $local)
                )) {
                    // caracter n�o valido na parte do endere�o
                    if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
                        $this->mailERROR .= "$email - Caracter n�o v�lido na parte do endere�o.\n";
                        $isValid = false;
                    }
                }
                if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
                    // dominio n�o encontrado no DNS
                    $this->mailERROR .= "$email - O dom�nio n�o foi encontrado no DNS.\n";
                    $isValid = false;
                }
            }
        }
        return $isValid;
    } //fim fun��o validEmailAdd


    /**
     * buscaEmail
     * M�todo que recupera o XML da NFe do email e o coloca na pasta "recebidas"
     * para posterior valida��o e aceita��o via registro de evento na SEFAZ
     * 1 - caso na caixa postal tenha email com xml de NFe, esse anexo ser�
     *     baixado e colocado na pasta "recebidas", e o email ser� movido para
     *     uma subpasta denominada pelo ANOMES da NFe;
     * 2 - caso na caixa postal tenha email sem anexos, ou os anexos n�o
     *     sejam NFe (em xml) o email ser� simplesmente deletado;
     * 3 - caso na caixa postal tenha email com xml de NFe e n�o seja possivel
     *     salvar o xml na pasta do sistema o email n�o ser� movido nem deletado
     *
     * @name buscaEmail
     * @return boolean True se verdadeiro ou false caso haja algum erro
     */
    public function buscaEmail()
    {
        if (!is_dir($this->recebidasDir) ||
                $this->CNPJ == '' ||
                $this->mailIMAPhost == '' ||
                $this->mailUSER == '' ||
                $this->mailPASS == ''
        ) {
            $this->mailERROR = "Faltam dados de configura��o ou existem erros na configura��o \n";
            return false;
        }
        //abre a conex�o IMAP
        $porta = !empty($this->mailIMAPport) ? ':'.$this->mailIMAPport : ':143';
        $security = !empty($this->mailIMAPsecurity) ? '/'.$this->mailIMAPsecurity : '';
        $nocerts = !empty($this->mailIMAPnocerts) ? '/'.$this->mailIMAPnocerts : '';
        $stream = "{".$this->mailIMAPhost.$porta.$security.$nocerts."}".$this->mailIMAPbox;
        $objMail = imap_open($stream, $this->mailUSER, $this->mailPASS);
        if ($objMail === false) {
            $this->mailERROR = "Falha na conex�o IMAP \n";
            return false;
        } else {
            //obter a lista de pastas existentes na caixa postal
            $list =  imap_list($objMail, '{'.$this->mailIMAPhost.'}', "*");
            if (is_array($list)) {
                foreach ($list as $val) {
                    $pasta = str_replace(
                        array(
                        '{'.$this->mailIMAPhost.'}'.$this->mailIMAPbox.'.',
                        '{'.$this->mailIMAPhost.'}'.$this->mailIMAPbox),
                        "",
                        imap_utf7_decode($val)
                    );
                    if ($pasta != '') {
                        $folders[] = $pasta;
                    }
                }
            } else {
                $this->mailERROR = "Falha na listagem de pastas imap_list : " . imap_last_error() . "\n";
                return false;
            }
            sort($folders);
            //obter o total de mensagens na caixa de entrada
            $qtde = imap_num_msg($objMail);
            for ($i = 1; $i <= $qtde; $i++) {
                //obter o identificador de cada mensagem
                $uid = imap_uid($objMail, $i);
                //obter o resumo da mensagem
                $result = imap_fetch_overview($objMail, $uid, FT_UID);
                //pegar o numero da mensagem na caixa postal para buscar anexos
                $msgno = $result[0]->msgno;
                //marca sem xml em anexo
                $flagXML = 0;
                //marca xml como n�o salvo
                $flagNFeSalva = 0;
                //buscar os anexos da mensagem
                $anexos = $this->getAnexosXML($objMail, $msgno);
                for ($j = 0; $j < count($anexos); $j++) {
                    $dom = new DOMDocument(); //cria objeto DOM
                    $dom->formatOutput = false;
                    $dom->preserveWhiteSpace = false;
                    $dom->loadXML($anexos[$j], LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                    $NFe = $dom->getElementsByTagName("NFe")->item(0);
                    if (!is_null($NFe)) {
                        //� uma NFe
                        $infNFe = $dom->getElementsByTagName("infNFe")->item(0);
                        $idNFe = str_replace('NFe', '', $infNFe->getAttribute("Id"));
                        $protNFe = $dom->getElementsByTagName("protNFe")->item(0);
                        $dEmi = $dom->getElementsByTagName("dEmi")->item(0)->nodeValue;
                        if (isset($dEmi)) {
                            $anomes = substr(str_replace('-', '', $dEmi), 0, 6);
                        }
                        //verificar se � para a empresa
                        $dest = $dom->getElementsByTagName("dest")->item(0);
                        $destCNPJ = $dest->getElementsByTagName("CNPJ")->item(0)->nodeValue;
                        if ($destCNPJ == $this->CNPJ) {
                            //sim essa NFe � endere�ada a n�s
                            $flagXML = 1;
                            $nfeXML = $dom->saveXML();
                            $xmlname = $this->recebidasDir.'/'.$idNFe.'-nfe.xml';
                            //salva o xml na pasta correta
                            if (!file_put_contents($xmlname, $nfeXML)) {
                                $this->mailERROR = date("d-m-Y H:i:s").
                                        ' - NFe - Falha ao salvar o arquivo na pasta. - '.
                                        utf8_decode($result[0]->from)."\n";
                                return false;
                            } else {
                                chmod($xmlname, 0755);
                                $flagNFeSalva = 1;
                            }
                        } //fim if cnpj
                    }//fim if NFe
                }//fim for enexos
                if ($flagNFeSalva == 1) {
                    //a mensagem continha uma NFe v�lida e foi salva
                    //ent�o mover o email para uma outra pasta $anomes
                    //verificar se j� existe a pasta $anomes
                    $flagFolder = 0;
                    foreach ($folders as $f) {
                        if ($f == $anomes) {
                            $flagFolder = 1;
                        }
                    }
                    if (!$flagFolder) {
                        //criar uma pasta $anomes na caixa postal
                        $stream = "{".$this->mailIMAPhost."}".$this->mailIMAPbox.'.'.$anomes;
                        if (imap_createmailbox($objMail, imap_utf7_encode($stream))) {
                            $flagFolder = 1;
                            $folders[]=$anomes;
                            sort($folders);
                            if (imap_mail_move($objMail, "$msgno:$msgno", "$this->mailIMAPbox.$anomes")) {
                                //imap_delete($objMail, $result[0]->uid, FT_UID);
                                $this->mailERROR .= 'A mensagem '.$result[0]->msgno .
                                        ' enviada por ' . $result[0]->from .
                                        ' em ' .$result[0]->date . ' Assunto ' .
                                        $result[0]->subject .
                                        ". Foi movida para a caixa postal [ $anomes ] e o
                                            anexo foi salvo na pasta recebidas \n";
                            } else {
                                $this->mailERROR .= 'A mensagem n�o foi movida para a pasta IMAP ANOMES '.
                                        imap_last_error() . "\n";
                                return false;
                            }
                        } else {
                            $this->mailERROR .= 'N�o foi permitida a cria��o de nova pasta ANOMES';
                            return false;
                        }
                    } else {
                        //ou a pasta j� existia ou foi agora criada
                        //para permitir mover a mensagem para esta pasta
                        if (imap_mail_move($objMail, "$msgno:$msgno", "$this->mailIMAPbox.$anomes")) {
                            //imap_delete($objMail, $result[0]->uid, FT_UID);
                            $this->mailERROR .= 'A mensagem '.$result[0]->msgno . ' enviada por ' .
                                    $result[0]->from . ' em ' .
                                    $result[0]->date . ' Assunto ' .
                                    $result[0]->subject .
                                    ". Foi movida para a caixa postal [ $anomes ] e o
                                        anexo foi salvo na pasta recebidas \n";
                        } else {
                            $this->mailERROR .= 'A mensagem n�o foi movida para a pasta IMAP ANOMES '.
                                    imap_last_error() . "\n";
                            return false;
                        }
                    }
                } else {
                    if ($flagXML == 1) {
                        //a mensagem continha um xml de NFe v�lido mas falhou ao ser salva no diretorio
                        //ent�o manter a mensagem onde est�
                        $this->mailERROR .= 'A mensagem '.$result[0]->msgno . ' enviada por ' .
                                $result[0]->from . ' em ' .
                                $result[0]->date . ' Assunto ' .
                                $result[0]->subject .
                                ". Foi mantida na caixa postal por falha na grava��o do xml \n";
                    } else {
                        //a mensagem n�o continha um xml de NFe v�lido
                        //ent�o marcar para deletar a mensagem da caixa postal
                        if (imap_delete($objMail, $uid, FT_UID)) {
                            $this->mailERROR .= 'A mensagem '.$result[0]->msgno . ' enviada por ' .
                                    $result[0]->from . ' em ' .
                                    $result[0]->date . ' Assunto ' .
                                    $result[0]->subject . ". Foi apagada da caixa postal \n";
                        } else {
                            $this->mailERROR .= 'A mensagem '.$result[0]->msgno . ' enviada por ' .
                                    $result[0]->from . ' em ' .
                                    $result[0]->date . ' Assunto ' .
                                    $result[0]->subject .
                                    ". N�O FOI apagada da caixa postal " . imap_last_error() . "\n";
                            return false;
                        }
                    }
                }//fim if
            }//fim for mensagens
            //apaga todas as mensagens marcadas para dele��o
            imap_expunge($objMail);
            //fecha a conex�o IMAP
            imap_close($objMail);
        }//fim if
    }//fim buscaMail

    /**
     * getAnexosXML
     * M�todo que extrai os anexos xml do email e os retorna para posterior
     * processamento e arquivo
     *
     * @name getAnexosXML
     * @param object $connection Objeto da conex�o IMAP
     * @param integer $message_number Numero de ordem da mensagem na pasta IMAP
     * @return mixed vazio ou array
     */
    private function getAnexosXML($connection, $message_number)
    {
        $attachments = array();
        $structure = imap_fetchstructure($connection, $message_number);
        if (isset($structure->parts) && count($structure->parts) || $structure->ifdisposition == true) {
            if ($structure->ifdisposition && isset($structure->disposition)) {
                if ($structure->disposition == "attachment") {
                    $n = count($structure->parameters);
                }
            } else {
                $n = count($structure->parts);
            }
            for ($i = 0; $i < $n; $i++) {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'attachment' => ''
                );
                if ($structure->parts[$i]->ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }//fim foreach
                }//fim if
                if ($structure->parts[$i]->ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }//fim foreach
                }//fim if
                if ($structure->ifdisposition) {
                    if ($structure->disposition == "attachment") {
                        $object = $structure->parameters[$i];
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                            $structure->parts[$i] = $structure; //altera��o para n�o ter que mexer nas linhas abaixo.
                        }
                    }
                }//fim if
                if ($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($connection, $message_number, $i+1);
                    if ($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    } elseif ($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }//fim if
                }//fim if
            }//fim for
        }//fim if
        $j = 0;
        for ($i = 0; $i < $n; $i++) {
            //se o anexo existir e o arquivo tiver no final do seu nome .xml
            if ($attachments[$i]['is_attachment'] && strtolower(substr($attachments[$i]['filename'], -4)) == '.xml') {
                $anexos[$j] = $attachments[$i]['attachment'];
                $j++;
            }
        }
        /*
        Existiam outros tipos de anexo que n�o conseguiam ser lidos,
        Anexos interiores de uma mensagem com conteudo escrito,
        Seus anexos ficavam escondidos atr�s de parts->parts->parts,
        Seus conteudos dentro do fetchbody se escondiam em subpartes da mensagem,
        Sendo assim, caso o sistema n�o encontre nenhum anexos ele checa se os anexos
        est�o nessa situa��o.
        Rodrigo W Cardoso <rodrigogepem at gmail dot com>
        */
        if (empty($anexos[0])) {
            $a = 1;
            $k = 1;
            foreach ($structure->parts as $part1) {
                foreach ($part1->parts as $part2) {
                    $z = 1;
                    foreach ($part2->parts as $part3) {
                        foreach ($part3->parameters as $object) {
                            if (((strtolower($object->attribute)) == 'name') ||
                                    ((strtolower($object->attribute)) == 'filename')) {
                                $attachments[$k]['is_attachment'] = true;
                                $attachments[$k]['filename'] = $object->value;
                                $attachments[$k]['parts'] = $a;
                                $attachments[$k]['encoding'] = $part3->encoding;
                                $anexo = ($a).'.'.($z+1);
                                $attachments[$k]['attachment'] = imap_fetchbody($connection, $message_number, $anexo);
                                if ($attachments[$k]['encoding'] == 3) { // 3 = BASE64
                                    $attachments[$k]['attachment'] = base64_decode($attachments[$k]['attachment']);
                                } elseif ($attachments[$k]['encoding'] == 4) { // 4 = QUOTED-PRINTABLE
                                    $attachments[$k]['attachment'] =
                                            quoted_printable_decode($attachments[$k]['attachment']);
                                }//fim if
                                if ($attachments[$k]['is_attachment'] &&
                                        strtolower(substr($attachments[$k]['filename'], -4)) == '.xml') {
                                    $anexos[$j] = $attachments[$k]['attachment'];
                                    $j++;
                                }//fim if attachments
                                $z++;
                                $k++;
                            }//fim if attribute
                        }//fim foreach parameters
                    }//fim foreach part3
                }//fim foreach part2
                $a++;
            }//fim foreach part1
        }//fim if anexos empty
        return $anexos;
    }//fim getAnexosXML

    /**
     * html2txt
     * Remove as tags html para deixar em texto puro
     * @name    html2txt
     * @param   string $str
     * @return  string texto puro sem as tags html
     */
    private function html2txt($str = '')
    {
        //substituir todos os tags
        $str = str_replace('</p>', "\n", $str);
        $txt = strip_tags($str);
        return $txt;
    } //fim html2txt
}//fim da classe
