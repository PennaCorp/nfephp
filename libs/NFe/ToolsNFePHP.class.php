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
 * Est� atualizada para :
 *      PHP 5.3
 *      Vers�o 3.10 dos webservices da SEFAZ com comunica��o via SOAP 1.2
 *      e conforme Manual de Integra��o Vers�o 5
 *
 * Aten��o: Esta classe n�o mant�m a compatibilidade com a vers�o 2.00 da SEFAZ !!!
 *
 * @package   NFePHP
 * @name      ToolsNFePHP
 * @version   3.10.10-beta
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2012 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *
 *              Allan Rett <allanlimao at gmail dot com>
 *              Antonio Neykson Turbano de Souza <neykson at gmail dot com>
 *              Bernardo Silva <bernardo at datamex dot com dot br>
 *              Bruno Bastos <brunomauro at gmail dot com>
 *              Bruno Lima <brunofileh at gmail.com>
 *              Bruno Tadeu Porto <brunotporto at gmail dot com>
 *              Daniel Viana <daniellista at gmail dot com>
 *              Diego Mosela <diego dot caicai at gmail dot com>
 *              Edilson Carlos Belluomini <edilson at maxihelp dot com dot br>
 *              Eduardo Gusm�o <eduardo.intrasis at gmail dot com>
 *              Eduardo Pacheco <eduardo at onlyone dot com dot br>
 *              Fabio A. Silva <binhoouropreto at gmail dot com>
 *              Fabricio Veiga <fabriciostuff at gmail dot com>
 *              Felipe Bonato <montanhats at gmail dot com>
 *              Fernando Mertins <fernando dot mertins at gmail dot com>
 *              Gilmar de Paula Fiocca <gilmar at tecnixinfo dot com dot br>
 *              Giovani Paseto <giovaniw2 at gmail dot com>
 *              Giuliano Nascimento <giusoft at hotmail dot com>
 *              Glauber Cini <glaubercini at gmail dot com>
 *              Guilherme Filippo <guilherme at macromind dot com dot br>
 *              Jorge Luiz Rodrigues Tom� <jlrodriguestome at hotmail dot com>
 *              Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *              Mario Almeida <prog dot almeida at gmail.com>
 *              Nataniel Fiuza <natan at laxus dot com dot br>
 *              Odair Jose Santos Junior <odairsantosjunior at gmail dot com>
 *              Paulo Gabriel Coghi <paulocoghi at gmail dot com>
 *              Paulo Henrique Demori <phdemori at hotmail dot com>
 *              Rafael Stavarengo <faelsta at gmail dot com>
 *              Roberto Spadim <rspadim at gmail dot com>
 *              Romulo Cordeiro <rrromulo at gmail dot com>
 *              Vinicius L. Azevedo <vinilazev at gmail dot com>
 *              Walber da Silva Sales <eng dot walber at gmail dot com>
 *
 */
//define o caminho base da instala��o do sistema
if (!defined('PATH_ROOT')) {
    define('PATH_ROOT', dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR);
}

require_once PATH_ROOT.'libs/Common/CommonNFePHP.class.php';
require_once PATH_ROOT.'libs/Common/DomDocumentNFePHP.class.php';
require_once PATH_ROOT.'libs/Common/ExceptionNFePHP.class.php';

class ToolsNFePHP extends CommonNFePHP
{
    /**
     * Tipo de ambiente produ��o
     */
    const AMBIENTE_PRODUCAO = 1;
    /**
     * Tipo de ambiente homologa��o
     */
    const AMBIENTE_HOMOLOGACAO = 2;
    /**
     * Sefaz Virtual Ambiente Nacional (SVAN), alguns estados utilizam esta Sefaz Virtual.
     */
    const SVAN = 'SVAN';
    /**
     * Sefaz Virtual Rio Grande do Sul (SVRS), alguns estados utilizam esta Sefaz Virtual.
     */
    const SVRS = 'SVRS';
    /**
     * Sefaz Virtual de Conting�ncia Ambiente Nacional (SVC-AN)
     */
    const CONTINGENCIA_SVCAN = 'SVCAN';
    /**
     * Sefaz Virtual de Conting�ncia Rio Grande do Sul (SVC-RS)
     */
    const CONTINGENCIA_SVCRS = 'SVCRS';
    /**
     * raizDir
     * Diretorio raiz da API
     * @var string
     */
    public $raizDir = PATH_ROOT;
    /**
     * arqDir
     * Diretorio raiz de armazenamento das notas
     * @var string
     */
    public $arqDir = '';
    /**
     * pdfDir
     * Diretorio onde s�o armazenados temporariamente as notas em pdf
     * @var string
     */
    public $pdfDir = '';
    /**
     * entDir
     * Diretorio onde s�o armazenados temporariamente as notas criadas (em txt ou xml)
     * @var string
     */
    public $entDir = '';
    /**
     * valDir
     * Diretorio onde s�o armazenados temporariamente as notas j� validadas pela API
     * @var string
     */
    public $valDir = '';
    /**
     * repDir
     * Diretorio onde s�o armazenados as notas reprovadas na valida��o da API
     * @var string
     */
    public $repDir = '';
    /**
     * assDir
     * Diretorio onde s�o armazenados temporariamente as notas j� assinadas
     * @var string
     */
    public $assDir = '';
    /**
     * envDir
     * Diretorio onde s�o armazenados temporariamente as notas enviadas
     * @var string
     */
    public $envDir = '';
    /**
     * aprDir
     * Diretorio onde s�o armazenados temporariamente as notas aprovadas
     * @var string
     */
    public $aprDir = '';
    /**
     * denDir
     * Diretorio onde s�o armazenados as notas denegadas
     * @var string
     */
    public $denDir = '';
    /**
     * rejDir
     * Diretorio onde s�o armazenados os retornos e as notas com as rejeitadas ap�s o envio do lote
     * @var string
     */
    public $rejDir = '';
    /**
     * canDir
     * Diretorio onde s�o armazenados os pedidos e respostas de cancelamento
     * @var string
     */
    public $canDir = '';
    /**
     * inuDir
     * Diretorio onde s�o armazenados os pedidos de inutiliza��o de numeros de notas
     * @var string
     */
    public $inuDir = '';
    /**
     * cccDir
     * Diretorio onde s�o armazenados os pedidos das cartas de corre��o
     * @var string
     */
    public $cccDir = '';
    /**
     * evtDir
     * Diretorio de arquivos dos eventos como as Manuifeta��es do Destinat�rio
     * @var string
     */
    public $evtDir = '';
    /**
     * dpcDir
     * Diretorio de arquivos dos DPEC
     * @var string
     */
    public $dpcDir = '';
    /**
     * tempDir
     * Diretorio de arquivos temporarios ou n�o significativos para a opera��o do sistema
     * @var string
     */
    public $temDir = '';
    /**
     * recDir
     * Diretorio de arquivos temporarios das NFe recebidas de terceiros
     * @var string
     */
    public $recDir = '';
    /**
     * conDir
     * Diretorio de arquivos das notas recebidas de terceiros e j� validadas
     * @var string
     */
    public $conDir = '';
    /**
     * certsDir
     * Diretorio onde est�o os certificados
     * @var string
     */
    public $certsDir = '';
    /**
     * imgDir
     * Diretorios com a imagens, fortos, logos, etc..
     * @var string
     */
    public $imgDir = '';
    /**
     * xsdDir
     * diretorio que contem os esquemas de valida��o
     * estes esquemas devem ser mantidos atualizados
     * @var string
     */
    public $xsdDir = '';
    /**
     * xmlURLfile
     * Arquivo XML com as URLs dos webservices das SEFAZ de todos dos Estados e
     * demais ambientes como AN, virtuais, etc.
     * @var string
     * @see /config/nfe_ws3_modXX.xml (arquivo novo, layout 3.10 da NF-e, onde
     * "XX" � o modelo da NF-e, "55" ou "65")
     */
    public $xmlURLfile = 'nfe_ws3_mod55.xml';
    /**
     * enableSVAN
     * Indica o acesso ao servi�o SVAN: Sefaz Virtual Ambiente Nacional
     * @var boolean
     */
    public $enableSVAN = false;
    /**
     * enableSVRS
     * Indica o acesso ao servi�o SVRS: Sefaz Virtual Rio Grande do Sul
     * @var boolean
     */
    public $enableSVRS = false;
    /**
     * enableSVCRS
     * Habilita conting�ncia ao servi�o SVC-RS: Sefaz Virtual de Conting�ncia Rio Grande do Sul
     * @var boolean
     */
    public $enableSVCRS = false;
    /**
     * enableSVCAN
     * Habilita conting�ncia ao servi�o SVC-AN: Sefaz Virtual de Conting�ncia Ambiente Nacional
     * @var boolean
     */
    public $enableSVCAN = false;
    /**
     * soapTimeout
     * Limite de tempo que o SOAP aguarda por uma conex�o
     * @var integer 0-indefinidamente ou numero de segundos
     */
    public $soapTimeout = 10;
    /**
     * Modelo da NF-e: 55 (NF-e) ou 65 (NFC-e), default 55 pois � a mais comum
     * atualmente
     * @var integer
     */
    public $modelo = 55;
    /**
     * tpAmb
     * Tipo de ambiente 1-produ��o 2-homologa��o
     * @var string
     */
    public $tpAmb = '';
    /**
     * schemeVer
     * String com o nome do subdiretorio onde se encontram os schemas
     * aten��o � case sensitive
     * @var string
     */
    protected $schemeVer = '';
    /**
     * aProxy
     * Matriz com as informa��es sobre o proxy da rede para uso pelo SOAP
     * @var array IP PORT USER PASS
     */
    public $aProxy = '';
    /**
     * keyPass
     * Senha de acesso a chave privada
     * @var string
     */
    private $keyPass = '';
    /**
     * passPhrase
     * palavra passe para acessar o certificado (normalmente n�o usada)
     * @var string
     */
    private $passPhrase = '';
    /**
     * certName
     * Nome do certificado digital
     * @var string
     */
    private $certName = '';
    /**
     * certMonthsToExpire
     * Meses que faltam para o certificado expirar
     * @var integer
     */
    public $certMonthsToExpire = 0;
    /**
     * certDaysToExpire
     * Dias que faltam para o certificado expirar
     * @var integer
     */
    public $certDaysToExpire = 0;
    /**
     * pfxTimeStamp
     * Timestamp da validade do certificado A1 PKCS12 .pfx
     * @var timestamp
     */
    private $pfxTimestamp = 0;
    /**
     * priKEY
     * Path completo para a chave privada em formato pem
     * @var string
     */
    protected $priKEY = '';
    /**
     * pubKEY
     * Path completo para a chave public em formato pem
     * @var string
     */
    protected $pubKEY = '';
    /**
     * certKEY
     * Path completo para o certificado (chave privada e publica) em formato pem
     * @var string
     */
    protected $certKEY = '';
    /**
     * empName
     * Raz�o social da Empresa
     * @var string
     */
    protected $empName = '';
    /**
     * cnpj
     * CNPJ do emitente
     * @var string
     */
    protected $cnpj = '';
    /**
     * cUF
     * C�digo da unidade da Federa��o IBGE
     * @var string
     */
    protected $cUF = '';
    /**
     * UF
     * Sigla da Unidade da Federa��o
     * @var string
     */
    protected $siglaUF = '';
    /**
     * timeZone
     * Zona de tempo GMT
     */
    protected $timeZone = '-03:00';
    /**
     * anoMes
     * Vari�vel que contem o ano com 4 digitos e o mes com 2 digitos
     * Ex. 201003
     * @var string
     */
    private $anoMes = '';
    /**
     * aURL
     * Array com as URLs dos webservices da SEFAZ
     * @var array
     */
    public $aURL = array();
    /**
     * aCabec
     * @var array
     */
    public $aCabec = array();
    /**
     * errMsg
     * Mensagens de erro do API
     * @var string
     */
    public $errMsg = '';
    /**
     * errStatus
     * Status de erro
     * @var boolean
     */
    public $errStatus = false;
    /**
     * URLbase
     * Base da API
     * @var string
     */
    public $URLbase = '';
    /**
     * soapDebug
     * Mensagens de debug da comunica��o SOAP
     * @var string
     */
    public $soapDebug = '';
    /**
     * debugMode
     * Ativa ou desativa as mensagens de debug da classe
     * @var integer
     */
    protected $debugMode = 2;
     /**
     * URLxsi
     * Inst�ncia do WebService
     * @var string
     */
    private $URLxsi = 'http://www.w3.org/2001/XMLSchema-instance';
    /**
     * URLxsd
     * Inst�ncia do WebService
     * @var string
     */
    private $URLxsd = 'http://www.w3.org/2001/XMLSchema';
    /**
     * URLnfe
     * Inst�ncia do WebService
     * @var string
     */
    private $URLnfe = 'http://www.portalfiscal.inf.br/nfe';
    /**
     * URLdsig
     * Inst�ncia do WebService
     * @var string
     */
    private $URLdsig = 'http://www.w3.org/2000/09/xmldsig#';
    /**
     * URLCanonMeth
     * Inst�ncia do WebService
     * @var string
     */
    private $URLCanonMeth = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * URLSigMeth
     * Inst�ncia do WebService
     * @var string
     */
    private $URLSigMeth = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    /**
     * URLTransfMeth_1
     * Inst�ncia do WebService
     * @var string
     */
    private $URLTransfMeth_1 = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    /**
     * URLTransfMeth_2
     * Inst�ncia do WebService
     * @var string
     */
    private $URLTransfMeth_2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * URLDigestMeth
     * Inst�ncia do WebService
     * @var string
     */
    private $URLDigestMeth = 'http://www.w3.org/2000/09/xmldsig#sha1';
    /**
     * URLPortal
     * Inst�ncia do WebService
     * @var string
     */
    private $URLPortal = 'http://www.portalfiscal.inf.br/nfe';
    /**
     * aliaslist
     * Lista dos aliases para os estados que usam Sefaz pr�pria ou Sefaz Virtual
     * @var array
     */
    private $aliaslist = array(
        //unidades da Federa��o:
        'AC'=>'SVRS',
        'AL'=>'SVRS',
        'AM'=>'AM',
        'AN'=>'AN',
        'AP'=>'SVRS',
        'BA'=>'BA',
        'CE'=>'CE',
        'DF'=>'SVRS',
        'ES'=>'SVRS',
        'GO'=>'GO',
        'MA'=>'SVAN',
        'MG'=>'MG',
        'MS'=>'MS',
        'MT'=>'MT',
        'PA'=>'SVAN',
        'PB'=>'SVRS',
        'PE'=>'PE',
        'PI'=>'SVAN',
        'PR'=>'PR',
        'RJ'=>'SVRS',
        'RN'=>'SVRS',
        'RO'=>'SVRS',
        'RR'=>'SVRS',
        'RS'=>'RS',
        'SC'=>'SVRS',
        'SE'=>'SVRS',
        'SP'=>'SP',
        'TO'=>'SVRS',
        //demais autorizadores do projeto NF-e:
        'SVAN'=>'SVAN',
        'SVRS'=>'SVRS',
        'SVCAN'=>'SVCAN',
        'SVCRS'=>'SVCRS');
    /**
     * cUFlist
     * Lista dos numeros identificadores dos estados
     * @var array
     */
    private $cUFlist = array('AC'=>'12',
        'AL'=>'27',
        'AM'=>'13',
        'AP'=>'16',
        'BA'=>'29',
        'CE'=>'23',
        'DF'=>'53',
        'ES'=>'32',
        'GO'=>'52',
        'MA'=>'21',
        'MG'=>'31',
        'MS'=>'50',
        'MT'=>'51',
        'PA'=>'15',
        'PB'=>'25',
        'PE'=>'26',
        'PI'=>'22',
        'PR'=>'41',
        'RJ'=>'33',
        'RN'=>'24',
        'RO'=>'11',
        'RR'=>'14',
        'RS'=>'43',
        'SC'=>'42',
        'SE'=>'28',
        'SP'=>'35',
        'TO'=>'17',
        'SVAN'=>'91'); // TODO fmertins 22/08: n�o existe c�digo da SVRS?

    /**
     * ctgList
     * Lista de relacionamento dos estados com os sistemas de continv�ncia
     * @var array
     */
    private $ctgList = array(
        'AC'=>'SVCAN',
        'AL'=>'SVCAN',
        'AM'=>'SVCAN',
        'AP'=>'SVCRS',
        'BA'=>'SVCRS',
        'CE'=>'SVCRS',
        'DF'=>'SVCAN',
        'ES'=>'SVCRS',
        'GO'=>'SVCRS',
        'MA'=>'SVSRS',
        'MG'=>'SVCAN',
        'MS'=>'SVCRS',
        'MT'=>'SVCRS',
        'PA'=>'SVCRS',
        'PB'=>'SVCAN',
        'PE'=>'SVCRS',
        'PI'=>'SVCRS',
        'PR'=>'SVCRS',
        'RJ'=>'SVCAN',
        'RN'=>'SVCRS',
        'RO'=>'SVCAN',
        'RR'=>'SVCAN',
        'RS'=>'SVCAN',
        'SC'=>'SVCAN',
        'SE'=>'SVCAN',
        'SP'=>'SVCAN',
        'TO'=>'SVCAN');

    /**
     * siglaUFList
     * Lista dos numeros identificadores dos estados
     * @var array
     */
    private $siglaUFList = array('11'=>'RO',
                                 '12'=>'AC',
                                 '13'=>'AM',
                                 '14'=>'RR',
                                 '15'=>'PA',
                                 '16'=>'AP',
                                 '17'=>'TO',
                                 '21'=>'MA',
                                 '22'=>'PI',
                                 '23'=>'CE',
                                 '24'=>'RN',
                                 '25'=>'PB',
                                 '26'=>'PE',
                                 '27'=>'AL',
                                 '28'=>'SE',
                                 '29'=>'BA',
                                 '31'=>'MG',
                                 '32'=>'ES',
                                 '33'=>'RJ',
                                 '35'=>'SP',
                                 '41'=>'PR',
                                 '42'=>'SC',
                                 '43'=>'RS',
                                 '50'=>'MS',
                                 '51'=>'MT',
                                 '52'=>'GO',
                                 '53'=>'DF',
                                 '91'=>'SVAN');
    /**
     * tzUFlist
     * Lista das zonas de tempo para os estados brasileiros
     * @var array
     */
    private $tzUFlist = array('AC'=>'America/Rio_Branco',
                              'AL'=>'America/Sao_Paulo',
                              'AM'=>'America/Manaus',
                              'AP'=>'America/Sao_Paulo',
                              'BA'=>'America/Bahia',
                              'CE'=>'America/Fortaleza',
                              'DF'=>'America/Sao_Paulo',
                              'ES'=>'America/Sao_Paulo',
                              'GO'=>'America/Sao_Paulo',
                              'MA'=>'America/Sao_Paulo',
                              'MG'=>'America/Sao_Paulo',
                              'MS'=>'America/Campo_Grande',
                              'MT'=>'America/Cuiaba',
                              'PA'=>'America/Belem',
                              'PB'=>'America/Sao_Paulo',
                              'PE'=>'America/Recife',
                              'PI'=>'America/Sao_Paulo',
                              'PR'=>'America/Sao_Paulo',
                              'RJ'=>'America/Sao_Paulo',
                              'RN'=>'America/Sao_Paulo',
                              'RO'=>'America/Porto_Velho',
                              'RR'=>'America/Boa_Vista',
                              'RS'=>'America/Sao_Paulo',
                              'SC'=>'America/Sao_Paulo',
                              'SE'=>'America/Sao_Paulo',
                              'SP'=>'America/Sao_Paulo',
                              'TO'=>'America/Sao_Paulo');
    /**
     * aMail
     * Matriz com os dados para envio de emails
     * FROM HOST USER PASS
     * @var array
     */
    public $aMail = array();
    /**
     * logopath
     * Vari�vel que contem o path completo para a logo a ser impressa na DANFE
     * @var string $logopath
     */
    public $danfelogopath = '';
    /**
     * danfelogopos
     * Estabelece a posi��o do logo no DANFE
     * L-Esquerda C-Centro e R-Direita
     * @var string
     */
    public $danfelogopos = 'C';
    /**
     * danfeform
     * Estabelece o formato do DANFE
     * P-Retrato L-Paisagem (NOTA: somente o formato P � funcional, por ora)
     * @var string P-retrato ou L-Paisagem
     */
    public $danfeform = 'P';
    /**
     * danfepaper
     * Estabelece o tamanho da p�gina
     * NOTA: somente o A4 pode ser utilizado de acordo com a ISO
     * @var string
     */
    public $danfepaper = 'A4';
    /**
     * danfecanhoto
     * Estabelece se o canhoto ser� impresso ou n�o
     * @var boolean
     */
    public $danfecanhoto = true;
    /**
     * danfefont
     * Estabelece a fonte padr�o a ser utilizada no DANFE
     * de acordo com o Manual da SEFAZ usar somente Times
     * @var string
     */
    public $danfefont = 'Times';
    /**
     * danfeprinter
     * Estabelece a printer padr�o a ser utilizada na impress�o da DANFE
     * @var string
     */
    public $danfeprinter = '';
    /**
     * exceptions
     * Ativa ou desativa o uso de exce��es para transporte de erros
     * @var boolean
     */
    protected $exceptions = false;

    /**
     * construct
     * M�todo construtor da classe
     * Este m�todo utiliza o arquivo de configura��o localizado no diretorio config
     * para montar os diret�rios e v�rias propriedades internas da classe, permitindo
     * automatizar melhor o processo de comunica��o com o SEFAZ.
     *
     * Este metodo pode estabelecer as configura��es a partir do arquivo config.php ou
     * atrav�s de um array passado na instancia��o da classe.
     *
     * @param array   $aConfig   Opcional dados de configura��o
     * @param integer $mododebug Opcional 2-N�o altera nenhum par�metro 1-SIM ou 0-N�O (2 default)
     * @param boolean $exceptions Opcional Utilize true para gerar exce��es
     * @return boolean true sucesso false Erro
     */
    public function __construct($aConfig = '', $mododebug = 1, $exceptions = true)
    {
        if (is_numeric($mododebug)) {
            $this->debugMode = $mododebug;
        }
        if ($mododebug == 1) {
            //ativar modo debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }
        if ($mododebug == 0) {
            //desativar modo debug
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        if ($exceptions) {
            $this->exceptions = true;
        }
        //verifica se foi passado uma matriz de configura��o na inicializa��o da classe
        if (is_array($aConfig)) {
            $this->tpAmb=$aConfig['ambiente'];
            $this->empName=$aConfig['empresa'];
            $this->siglaUF=$aConfig['UF'];
            $this->cUF=$this->cUFlist[$aConfig['UF']];
            $this->cnpj=$aConfig['cnpj'];
            $this->pubKEY = $aConfig['pubKey'];
            $this->priKEY = $aConfig['priKey'];
            $this->certKEY = $aConfig['certKey'];
            $this->keyPass=$aConfig['keyPass'];
            $this->arqDir = $aConfig['arquivosDir'];
            $this->xmlURLfile = $aConfig['arquivoURLxml'];
            //atribui o modelo automaticamente a partir do nome do arquivo XML
            $this->modelo = substr($this->xmlURLfile, -6, 2);
            $this->URLbase = $aConfig['baseurl'];
            $this->danfelogopath = $aConfig['danfeLogo'];
            $this->danfelogopos = $aConfig['danfeLogoPos'];
            $this->danfeform = $aConfig['danfeFormato'];
            $this->danfepaper = $aConfig['danfePapel'];
            $this->danfecanhoto = $aConfig['danfeCanhoto'];
            $this->danfefont = $aConfig['danfeFonte'];
            $this->danfeprinter = $aConfig['danfePrinter'];
            $this->schemeVer = $aConfig['schemes'];
            if (isset($aConfig['certsDir'])) {
                $this->certsDir =  $aConfig['certsDir'];
            }
            if ($aConfig['proxyIP'] != '') {
                $this->aProxy = array(
                    'IP'=>$aConfig['proxyIP'],
                    'PORT'=>$aConfig['proxyPORT'],
                    'USER'=>$aConfig['proxyUSER'],
                    'PASS'=>$aConfig['proxyPASS']);
            }
            if ($aConfig['mailFROM'] != '') {
                $this->aMail = array(
                    'mailFROM'=>$aConfig['mailFROM'],
                    'mailHOST'=>$aConfig['mailHOST'],
                    'mailUSER'=>$aConfig['mailUSER'],
                    'mailPASS'=>$aConfig['mailPASS'],
                    'mailPROTOCOL'=>$aConfig['mailPROTOCOL'],
                    'mailFROMmail'=>$aConfig['mailFROMmail'],
                    'mailFROMname'=>$aConfig['mailFROMname'],
                    'mailREPLYTOmail'=>$aConfig['mailREPLYTOmail'],
                    'mailREPLYTOname'=>$aConfig['mailREPLYTOname']);
            }
        } else {
            throw new nfephpException("Os par?metros n?o foram informados");
        }
        //estabelece o ambiente
        $sAmb = ($this->tpAmb == self::AMBIENTE_HOMOLOGACAO) ? 'homologacao' : 'producao';
        //carrega propriedade com ano e mes ex. 200911
        $this->anoMes = date('Ym');
        //carrega o caminho para os schemas
        $this->xsdDir = $this->raizDir.'schemes'. DIRECTORY_SEPARATOR;
        //carrega o caminho para os certificados caso n�o tenha sido passado por config
        if (empty($this->certsDir)) {
            $this->certsDir =  $this->raizDir.'certs'. DIRECTORY_SEPARATOR;
        }
        //carrega o caminho para as imegens
        $this->imgDir =  $this->raizDir.'images'. DIRECTORY_SEPARATOR;
        //verifica o ultimo caracter da vari�vel $arqDir
        // se n�o for um DIRECTORY_SEPARATOR ent�o colocar um
        if (substr($this->arqDir, -1, 1) != DIRECTORY_SEPARATOR) {
            $this->arqDir .= DIRECTORY_SEPARATOR;
        }
        // monta a estrutura de diretorios utilizados na manipula��o das NFe
        $this->entDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'entradas'.DIRECTORY_SEPARATOR;
        $this->assDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'assinadas'.DIRECTORY_SEPARATOR;
        $this->valDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'validadas'.DIRECTORY_SEPARATOR;
        $this->rejDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'rejeitadas'.DIRECTORY_SEPARATOR;
        $this->envDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'enviadas'.DIRECTORY_SEPARATOR;
        $this->aprDir=$this->envDir.'aprovadas'.DIRECTORY_SEPARATOR;
        $this->denDir=$this->envDir.'denegadas'.DIRECTORY_SEPARATOR;
        $this->repDir=$this->envDir.'reprovadas'.DIRECTORY_SEPARATOR;
        $this->canDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'canceladas'.DIRECTORY_SEPARATOR;
        $this->inuDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'inutilizadas'.DIRECTORY_SEPARATOR;
        $this->cccDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'cartacorrecao'.DIRECTORY_SEPARATOR;
        $this->evtDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'eventos'.DIRECTORY_SEPARATOR;
        $this->dpcDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'dpec'.DIRECTORY_SEPARATOR;
        $this->temDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'temporarias'.DIRECTORY_SEPARATOR;
        $this->recDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'recebidas'.DIRECTORY_SEPARATOR;
        $this->conDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'consultadas'.DIRECTORY_SEPARATOR;
        $this->pdfDir=$this->arqDir.$sAmb.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR;
        //monta a arvore de diret�rios necess�ria e estabelece permiss�es de acesso
        if (!is_dir($this->arqDir)) {
            mkdir($this->arqDir, 0777);
        }
        if (!is_dir($this->arqDir.DIRECTORY_SEPARATOR.$sAmb)) {
            mkdir($this->arqDir.DIRECTORY_SEPARATOR.$sAmb, 0777);
        }
        if (!is_dir($this->entDir)) {
            mkdir($this->entDir, 0777);
        }
        if (!is_dir($this->assDir)) {
            mkdir($this->assDir, 0777);
        }
        if (!is_dir($this->valDir)) {
            mkdir($this->valDir, 0777);
        }
        if (!is_dir($this->rejDir)) {
            mkdir($this->rejDir, 0777);
        }
        if (!is_dir($this->envDir)) {
            mkdir($this->envDir, 0777);
        }
        if (!is_dir($this->aprDir)) {
            mkdir($this->aprDir, 0777);
        }
        if (!is_dir($this->denDir)) {
            mkdir($this->denDir, 0777);
        }
        if (!is_dir($this->repDir)) {
            mkdir($this->repDir, 0777);
        }
        if (!is_dir($this->canDir)) {
            mkdir($this->canDir, 0777);
        }
        if (!is_dir($this->inuDir)) {
            mkdir($this->inuDir, 0777);
        }
        if (!is_dir($this->cccDir)) {
            mkdir($this->cccDir, 0777);
        }
        if (!is_dir($this->evtDir)) {
            mkdir($this->evtDir, 0777);
        }
        if (!is_dir($this->dpcDir)) {
            mkdir($this->dpcDir, 0777);
        }
        if (!is_dir($this->temDir)) {
            mkdir($this->temDir, 0777);
        }
        if (!is_dir($this->recDir)) {
            mkdir($this->recDir, 0777);
        }
        if (!is_dir($this->conDir)) {
            mkdir($this->conDir, 0777);
        }
        if (!is_dir($this->pdfDir)) {
            mkdir($this->pdfDir, 0777);
        }
        //carrega um array com os dados para acesso aos WebServices SEFAZ
        if (!$this->aURL = $this->pLoadSEFAZ($this->tpAmb, $this->siglaUF)) {
            $msg = "Erro no carregamento das informacoes da SEFAZ: $this->errMsg";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //definir o timezone default para o estado do emitente
        $timezone = $this->tzUFlist[$this->siglaUF];
        date_default_timezone_set($timezone);
        //estados que participam do horario de ver�o
        $aUFhv = array('ES','DF','GO','MG','MS','PR','RJ','RS','SP','SC');
        //corrigir o timeZone
        if ($this->siglaUF == 'AC' ||
            $this->siglaUF == 'AM' ||
            $this->siglaUF == 'MT' ||
            $this->siglaUF == 'MS' ||
            $this->siglaUF == 'RO' ||
            $this->siglaUF == 'RR') {
            $this->timeZone = '-04:00';
        }
        //verificar se estamos no hor�rio de ver�o *** depende da configura��o do servidor ***
        if (date('I') == 1) {
            //estamos no horario de ver�o verificar se o estado est� incluso
            if (in_array($this->siglaUF, $aUFhv)) {
                $itz = (int) $this->timeZone;
                $itz++;
                $this->timeZone = '-'.sprintf("%02d", abs($itz)).':00';
                //poderia ser obtido com date('P')
            }
        }//fim check horario verao
        return true;
    } //fim construct

    /**
     * validXML
     * Verifica o xml com base no xsd
     * Esta fun��o pode validar qualquer arquivo xml do sistema de NFe
     * H� um bug no libxml2 para vers�es anteriores a 2.7.3
     * que causa um falso erro na valida��o da NFe devido ao
     * uso de uma marca��o no arquivo tiposBasico_v1.02.xsd
     * onde se le {0 , } substituir por *
     * A valida��o n�o deve ser feita ap�s a inclus�o do protocolo !!!
     * Caso seja passado uma NFe ainda n�o assinada a falta da assinatura ser� desconsiderada.
     * @name validXML
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param    string  $xml  string contendo o arquivo xml a ser validado ou seu path
     * @param    string  $xsdfile Path completo para o arquivo xsd
     * @param    array   $aError Vari�vel passada como referencia ir� conter as mensagens de erro se houverem
     * @return   boolean
     */
    public function validXML($xml = '', $xsdFile = '', &$aError = array())
    {
        try {
            $flagOK = true;
            // Habilita a manipula�ao de erros da libxml
            libxml_use_internal_errors(true);
            //limpar erros anteriores que possam estar em mem�ria
            libxml_clear_errors();
            //verifica se foi passado o xml
            if (strlen($xml)==0) {
                $msg = 'Voc� deve passar o conteudo do xml assinado como par�metro '
                       .'ou o caminho completo at� o arquivo.';
                $aError[] = $msg;
                throw new nfephpException($msg);
            }
            // instancia novo objeto DOM
            $dom = new DOMDocument('1.0', 'utf-8');
            $dom->preserveWhiteSpace = false; //elimina espa�os em branco
            $dom->formatOutput = false;
            // carrega o xml tanto pelo string contento o xml como por um path
            if (is_file($xml)) {
                $dom->load($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            } else {
                $dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            }
            // pega a assinatura
            $Signature = $dom->getElementsByTagName('Signature')->item(0);
            //recupera os erros da libxml
            $errors = libxml_get_errors();
            if (!empty($errors)) {
                //o dado passado como $docXml n�o � um xml
                $msg = 'O dado informado n�o � um XML ou n�o foi encontrado. '
                        . 'Voc� deve passar o conteudo de um arquivo xml assinado como par�metro.';
                $aError[] = $msg;
                throw new nfephpException($msg);
            }
            if ($xsdFile=='') {
                if (is_file($xml)) {
                    $contents = file_get_contents($xml);
                } else {
                    $contents = $xml;
                }
                $sxml = simplexml_load_string($contents);
                $nome = $sxml->getName();
                $sxml = null;
                //determinar qual o arquivo de schema v�lido
                //buscar o nome do scheme
                switch ($nome) {
                    case 'evento':
                        //obtem o node com a vers�o
                        $node = $dom->documentElement;
                        //obtem a vers�o do layout
                        $ver = trim($node->getAttribute("versao"));
                        $tpEvento = $node->getElementsByTagName('tpEvento')->item(0)->nodeValue;
                        switch ($tpEvento) {
                            case '110110':
                                //carta de corre��o
                                $xsdFile = "CCe_v$ver.xsd";
                                break;
                            default:
                                $xsdFile = "";
                                break;
                        }
                        break;
                    case 'envEvento':
                        //obtem o node com a vers�o
                        $node = $dom->getElementsByTagName('evento')->item(0);
                        //obtem a vers�o do layout
                        $ver = trim($node->getAttribute("versao"));
                        $tpEvento = $node->getElementsByTagName('tpEvento')->item(0)->nodeValue;
                        switch ($tpEvento) {
                            case '110110':
                                //carta de corre��o
                                $xsdFile = "envCCe_v$ver.xsd";
                                break;
                            default:
                                $xsdFile = "envEvento_v$ver.xsd";
                                break;
                        }
                        break;
                    case 'NFe':
                        //obtem o node com a vers�o
                        $node = $dom->getElementsByTagName('infNFe')->item(0);
                        //obtem a vers�o do layout
                        $ver = trim($node->getAttribute("versao"));
                        $xsdFile = "nfe_v$ver.xsd";
                        break;
                    case 'nfeProc':
                        //obtem o node com a vers�o
                        $node = $dom->documentElement;
                        //obtem a vers�o do layout
                        $ver = trim($node->getAttribute("versao"));
                        $xsdFile = "procNFe_v$ver.xsd";
                        break;
                    default:
                        //obtem o node com a vers�o
                        $node = $dom->documentElement;
                        //obtem a vers�o do layout
                        $ver = trim($node->getAttribute("versao"));
                        $xsdFile = $nome."_v".$ver.".xsd";
                        break;
                }
                $aFile = $this->listDir($this->xsdDir.$this->schemeVer.DIRECTORY_SEPARATOR, $xsdFile, true);
                if (empty($aFile) || empty($aFile[0])) {
                    $msg = "Erro na localiza��o do schema xsd.\n";
                    $aError[] = $msg;
                    throw new nfephpException($msg);
                } else {
                    $xsdFile = $aFile[0];
                }
            }
            //limpa erros anteriores
            libxml_clear_errors();
            // valida o xml com o xsd
            if (!$dom->schemaValidate($xsdFile)) {
                /**
                 * Se n�o foi poss�vel validar, voc� pode capturar
                 * todos os erros em um array
                 * Cada elemento do array $arrayErrors
                 * ser� um objeto do tipo LibXmlError
                 */
                // carrega os erros em um array
                $aIntErrors = libxml_get_errors();
                $flagOK = false;
                if (!isset($Signature)) {
                    // remove o erro de falta de assinatura
                    foreach ($aIntErrors as $k => $intError) {
                        if (strpos($intError->message, '( {http://www.w3.org/2000/09/xmldsig#}Signature )') !== false) {
                            // remove o erro da assinatura, se tiver outro meio melhor (atravez dos erros de codigo) e alguem souber como tratar por eles, por favor contribua...
                            unset($aIntErrors[$k]);
                        }
                    }
                    reset($aIntErrors);
                    $flagOK = true;
                }//fim teste Signature
                $msg = '';
                foreach ($aIntErrors as $intError) {
                    $flagOK = false;
                    $en = array("{http://www.portalfiscal.inf.br/nfe}"
                                ,"[facet 'pattern']"
                                ,"The value"
                                ,"is not accepted by the pattern"
                                ,"has a length of"
                                ,"[facet 'minLength']"
                                ,"this underruns the allowed minimum length of"
                                ,"[facet 'maxLength']"
                                ,"this exceeds the allowed maximum length of"
                                ,"Element"
                                ,"attribute"
                                ,"is not a valid value of the local atomic type"
                                ,"is not a valid value of the atomic type"
                                ,"Missing child element(s). Expected is"
                                ,"The document has no document element"
                                ,"[facet 'enumeration']"
                                ,"one of"
                                ,"failed to load external entity"
                                ,"Failed to locate the main schema resource at"
                                ,"This element is not expected. Expected is"
                                ,"is not an element of the set");

                    $pt = array(""
                                ,"[Erro 'Layout']"
                                ,"O valor"
                                ,"n�o � aceito para o padr�o."
                                ,"tem o tamanho"
                                ,"[Erro 'Tam. Min']"
                                ,"deve ter o tamanho m�nimo de"
                                ,"[Erro 'Tam. Max']"
                                ,"Tamanho m�ximo permitido"
                                ,"Elemento"
                                ,"Atributo"
                                ,"n�o � um valor v�lido"
                                ,"n�o � um valor v�lido"
                                ,"Elemento filho faltando. Era esperado"
                                ,"Falta uma tag no documento"
                                ,"[Erro 'Conte�do']"
                                ,"um de"
                                ,"falha ao carregar entidade externa"
                                ,"Falha ao tentar localizar o schema principal em"
                                ,"Este elemento n�o � esperado. Esperado �"
                                ,"n�o � um dos seguintes possiveis");

                    switch ($intError->level) {
                        case LIBXML_ERR_WARNING:
                            $aError[] = " Aten�ao $intError->code: ".str_replace($en, $pt, $intError->message);
                            break;
                        case LIBXML_ERR_ERROR:
                            $aError[] = " Erro $intError->code: ".str_replace($en, $pt, $intError->message);
                            break;
                        case LIBXML_ERR_FATAL:
                            $aError[] = " Erro Fatal $intError->code: ".str_replace($en, $pt, $intError->message);
                            break;
                    }
                    $msg .= str_replace($en, $pt, $intError->message);
                }
            } else {
                $flagOK = true;
            }
            if (!$flagOK) {
                throw new nfephpException($msg);
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    } //fim validXML

    /**
     * addProt
     * Adiciona a tag do protocolo a NFe, preparando a mesma para impress�o e envio ao destinat�rio.
     * Tamb�m pode ser usada para substituir o protocolo de autoriza��o
     * pelo protocolo de cancelamento, nesse caso apenas para a gest�o interna
     * na empresa, esse arquivo com o cancelamento n�o deve ser enviado ao cliente.
     *
     * @name addProt
     * @param string $nfefile path completo para o arquivo contendo a NFe
     * @param string $protfile path completo para o arquivo contendo o protocolo, cancelamento ou evento de cancelamento
     * @return string Retorna a NFe com o protocolo
     */
    public function addProt($nfefile = '', $protfile = '')
    {
        try {
            if ($nfefile == '' || $protfile == '') {
                $msg = 'Para adicionar o protocolo, ambos os caminhos devem ser passados.'
                       .' Para a nota e para o protocolo!';
                throw new nfephpException($msg);
            }
            if (!is_file($nfefile) || !is_file($protfile)) {
                $msg = 'Algum dos arquivos n�o foi localizado no caminho indicado ! '.$nfefile. ' ou ' .$protfile;
                throw new nfephpException($msg);
            }
            //carrega o arquivo na vari�vel
            $docnfe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $docnfe->formatOutput = false;
            $docnfe->preserveWhiteSpace = false;
            $xmlnfe = file_get_contents($nfefile);
            if (! $docnfe->loadXML($xmlnfe, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $msg = 'O arquivo indicado como NFe n�o � um XML! '.$nfefile;
                throw new nfephpException($msg);
            }
            $nfe = $docnfe->getElementsByTagName("NFe")->item(0);
            if (!isset($nfe)) {
                $msg = 'O arquivo indicado como NFe n�o � um xml de NFe! '.$nfefile;
                throw new nfephpException($msg);
            }
            $infNFe = $docnfe->getElementsByTagName("infNFe")->item(0);
            $versao = trim($infNFe->getAttribute("versao"));
            $chaveId = trim($infNFe->getAttribute("Id"));
            $chave = preg_replace('/[^0-9]/', '', $chaveId);
            $DigestValue = !empty($docnfe->getElementsByTagName('DigestValue')->item(0)->nodeValue) ? $docnfe->getElementsByTagName('DigestValue')->item(0)->nodeValue : '';
            if ($DigestValue == '') {
                $msg = 'O XML da NFe n�o est� assinado! '.$nfefile;
                throw new nfephpException($msg);
            }
            //carrega o protocolo e seus dados
            //protocolo do lote enviado
            $prot = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $prot->formatOutput = false;
            $prot->preserveWhiteSpace = false;
            $xmlprot = file_get_contents($protfile);
            if (! $prot->loadXML($xmlprot, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $msg = 'O arquivo indicado para ser protocolado na NFe � um XML! '.$protfile;
                throw new nfephpException($msg);
            }
            //protocolo de autoriza��o
            $protNFe = $prot->getElementsByTagName("protNFe")->item(0);
            if (isset($protNFe)) {
                $protver     = trim($protNFe->getAttribute("versao"));
                $tpAmb       = $protNFe->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                $verAplic    = $protNFe->getElementsByTagName("verAplic")->item(0)->nodeValue;
                $chNFe       = $protNFe->getElementsByTagName("chNFe")->item(0)->nodeValue;
                $dhRecbto    = $protNFe->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
                $nProt       = $protNFe->getElementsByTagName("nProt")->item(0)->nodeValue;
                $digVal      = $protNFe->getElementsByTagName("digVal")->item(0)->nodeValue;
                $cStat       = $protNFe->getElementsByTagName("cStat")->item(0)->nodeValue;
                $xMotivo     = $protNFe->getElementsByTagName("xMotivo")->item(0)->nodeValue;
                if ($DigestValue != $digVal) {
                    $msg = 'Inconsist�ncia! O DigestValue da NFe n�o combina com o do digVal do protocolo indicado!';
                    throw new nfephpException($msg);
                }
            }
            //cancelamento antigo
            $retCancNFe = $prot->getElementsByTagName("retCancNFe")->item(0);
            if (isset($retCancNFe)) {
                $protver     = trim($retCancNFe->getAttribute("versao"));
                $tpAmb       = $retCancNFe->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                $verAplic    = $retCancNFe->getElementsByTagName("verAplic")->item(0)->nodeValue;
                $chNFe       = $retCancNFe->getElementsByTagName("chNFe")->item(0)->nodeValue;
                $dhRecbto    = $retCancNFe->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
                $nProt       = $retCancNFe->getElementsByTagName("nProt")->item(0)->nodeValue;
                $cStat       = $retCancNFe->getElementsByTagName("cStat")->item(0)->nodeValue;
                $xMotivo     = $retCancNFe->getElementsByTagName("xMotivo")->item(0)->nodeValue;
                $digVal      = $DigestValue;
            }
            //cancelamento por evento NOVO
            $retEvento = $prot->getElementsByTagName("retEvento")->item(0);
            if (isset($retEvento)) {
                //verificar se se trata de cancelamento caso seja alterar o protocolo
                //se n�o deixar
                if ($retEvento->getElementsByTagName("tpEvento")->item(0)->nodeValue == '110111') {
                    $protver     = trim($retEvento->getAttribute("versao"));
                    $tpAmb       = $retEvento->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                    $verAplic    = $retEvento->getElementsByTagName("verAplic")->item(0)->nodeValue;
                    $chNFe       = $retEvento->getElementsByTagName("chNFe")->item(0)->nodeValue;
                    $dhRecbto    = $retEvento->getElementsByTagName("dhRegEvento")->item(0)->nodeValue;
                    $nProt       = $retEvento->getElementsByTagName("nProt")->item(0)->nodeValue;
                    $cStat       = $retEvento->getElementsByTagName("cStat")->item(0)->nodeValue;
                    $tpEvento    = $retEvento->getElementsByTagName("tpEvento")->item(0)->nodeValue;
                    $xMotivo     = $retEvento->getElementsByTagName("xMotivo")->item(0)->nodeValue;
                    $digVal      = $DigestValue;
                }
            }
            if (!isset($protNFe) && !isset($retCancNFe) && !isset($retEvento)) {
                $msg = 'O arquivo indicado para ser protocolado a NFe n�o � um protocolo nem de cancelamento! '
                       .$protfile;
                throw new nfephpException($msg);
            }
            if ($chNFe != $chave) {
                $msg = 'O protocolo indicado pertence a outra NFe ... os numertos das chaves n�o combinam !';
                throw new nfephpException($msg);
            }
            //cria a NFe processada com a tag do protocolo
            $procnfe = new DOMDocument('1.0', 'utf-8');
            $procnfe->formatOutput = false;
            $procnfe->preserveWhiteSpace = false;
            //cria a tag nfeProc
            $nfeProc = $procnfe->createElement('nfeProc');
            $procnfe->appendChild($nfeProc);
            //estabele o atributo de vers�o
            $nfeProc_att1 = $nfeProc->appendChild($procnfe->createAttribute('versao'));
            $nfeProc_att1->appendChild($procnfe->createTextNode($protver));
            //estabelece o atributo xmlns
            $nfeProc_att2 = $nfeProc->appendChild($procnfe->createAttribute('xmlns'));
            $nfeProc_att2->appendChild($procnfe->createTextNode($this->URLnfe));
            //inclui a tag NFe
            $node = $procnfe->importNode($nfe, true);
            $nfeProc->appendChild($node);
            //cria tag protNFe
            $protNFe = $procnfe->createElement('protNFe');
            $nfeProc->appendChild($protNFe);
            //estabele o atributo de vers�o
            $protNFe_att1 = $protNFe->appendChild($procnfe->createAttribute('versao'));
            $protNFe_att1->appendChild($procnfe->createTextNode($versao));
            //cria tag infProt
            $infProt = $procnfe->createElement('infProt');
            $infProt_att1 = $infProt->appendChild($procnfe->createAttribute('Id'));
            $infProt_att1->appendChild($procnfe->createTextNode('ID'.$nProt));
            $protNFe->appendChild($infProt);
            $infProt->appendChild($procnfe->createElement('tpAmb', $tpAmb));
            $infProt->appendChild($procnfe->createElement('verAplic', $verAplic));
            $infProt->appendChild($procnfe->createElement('chNFe', $chNFe));
            $infProt->appendChild($procnfe->createElement('dhRecbto', $dhRecbto));
            $infProt->appendChild($procnfe->createElement('nProt', $nProt));
            $infProt->appendChild($procnfe->createElement('digVal', $digVal));
            $infProt->appendChild($procnfe->createElement('cStat', $cStat));
            $infProt->appendChild($procnfe->createElement('xMotivo', $xMotivo));
            //salva o xml como string em uma vari�vel
            $procXML = $procnfe->saveXML();
            //remove as informa��es indesejadas
            $procXML = str_replace(
                array('default:',':default',"\n","\r","\s"),
                '',
                $procXML
            );
            $procXML = str_replace(
                'NFe xmlns="http://www.portalfiscal.inf.br/nfe" xmlns="http://www.w3.org/2000/09/xmldsig#"',
                'NFe xmlns="http://www.portalfiscal.inf.br/nfe"',
                $procXML
            );
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $procXML;
    } //fim addProt

    /**
     * addB2B
     * Adiciona o xml referente a comunica��o B2B � NFe, conforme padr�o ANFAVEA+GS1
     *
     * @param string $nfefile path para o arquivo com a nfe protocolada e autorizada
     * @param string $b2bfile path para o arquivo xml padr�o ANFAVEA+GS1 e NT2013_002
     * @param string $tagB2B Tag principar do xml B2B pode ser NFeB2B ou NFeB2BFin
     * @return mixed FALSE se houve erro ou xml com a nfe+b2b
     */
    public function addB2B($nfefile = '', $b2bfile = '', $tagB2B = '')
    {
        try {
            if ($nfefile == '' || $b2bfile == '') {
                $msg = 'Para adicionar o arquivo B2B, ambos os caminhos devem ser passados. Para a nota e para o B2B!';
                throw new nfephpException($msg);
            }
            if (!is_file($nfefile) || !is_file($b2bfile)) {
                $msg = 'Algum dos arquivos n�o foi localizado no caminho indicado ! '.$nfefile. ' ou ' .$b2bfile;
                throw new nfephpException($msg);
            }
            if ($tagB2B == '') {
                $tagB2B = 'NFeB2BFin'; //padr�o anfavea
            }
            //carrega o arquivo na vari�vel
            $docnfe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $docnfe->formatOutput = false;
            $docnfe->preserveWhiteSpace = false;
            $xmlnfe = file_get_contents($nfefile);
            if (!$docnfe->loadXML($xmlnfe, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $msg = 'O arquivo indicado como NFe n�o � um XML! '.$nfefile;
                throw new nfephpException($msg);
            }
            $nfeProc = $docnfe->getElementsByTagName("nfeProc")->item(0);
            if (!isset($nfeProc)) {
                $msg = 'O arquivo indicado como NFe n�o � um xml de NFe ou n�o cont�m o protocolo! '.$nfefile;
                throw new nfephpException($msg);
            }
            $infNFe = $docnfe->getElementsByTagName("infNFe")->item(0);
            $versao = trim($infNFe->getAttribute("versao"));
            $chaveId = trim($infNFe->getAttribute("Id"));
            $chave = preg_replace('/[^0-9]/', '', $chaveId);
            //carrega o arquivo B2B e seus dados
            //protocolo do lote enviado
            $b2b = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $b2b->formatOutput = false;
            $b2b->preserveWhiteSpace = false;
            $xmlb2b = file_get_contents($b2bfile);
            if (! $b2b->loadXML($xmlb2b, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $msg = 'O arquivo indicado como Protocolo n�o � um XML! '.$b2bfile;
                throw new nfephpException($msg);
            }
            $NFeB2BFin = $b2b->getElementsByTagName($tagB2B)->item(0);
            if (!isset($NFeB2BFin)) {
                $msg = 'O arquivo indicado como B2B n�o � um XML de B2B! '.$b2bfile;
                throw new nfephpException($msg);
            }
            //cria a NFe processada com a tag do protocolo
            $procb2b = new DOMDocument('1.0', 'utf-8');
            $procb2b->formatOutput = false;
            $procb2b->preserveWhiteSpace = false;
            //cria a tag nfeProc
            $nfeProcB2B = $procb2b->createElement('nfeProcB2B');
            $procb2b->appendChild($nfeProcB2B);
            //inclui a tag NFe
            $node = $procb2b->importNode($nfeProc, true);
            $nfeProcB2B->appendChild($node);
            //inclui a tag NFeB2BFin
            $node = $procb2b->importNode($NFeB2BFin, true);
            $nfeProcB2B->appendChild($node);
            //salva o xml como string em uma vari�vel
            $nfeb2bXML = $procb2b->saveXML();
            //remove as informa��es indesejadas
            $nfeb2bXML = str_replace(array("\n","\r","\s"), '', $nfeb2bXML);
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $nfeb2bXML;
    }//fim addB2B

    /**
     * signXML
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * os arquivos XML
     *
     * @name signXML
     * @param  mixed $docxml Path para o arquivo xml ou String contendo o arquivo XML a ser assinado
     * @param  string $tagid TAG do XML que devera ser assinada
     * @return mixed false se houve erro ou string com o XML assinado
     */
    public function signXML($docxml, $tagid = '')
    {
        try {
            if ($tagid == '') {
                $msg = "Uma tag deve ser indicada para que seja assinada!!";
                throw new nfephpException($msg);
            }
            if ($docxml == '') {
                $msg = "Um xml deve ser passado para que seja assinado!!";
                throw new nfephpException($msg);
            }
            if ($this->priKEY == "") {
                $msg = "A chave privada parece invalida, verifique!!";
                throw new nfephpException($msg);
            }
            if (is_file($docxml)) {
                $xml = file_get_contents($docxml);
            } else {
                $xml = $docxml;
            }
            //obter a chave privada para a assinatura
            //modificado para permitir a leitura de arquivos maiores
            //que o normal que � cerca de 2kBytes.
            /*if (! $filep = fopen($this->priKEY, "r")) {
                $msg = "Erro ao ler arquivo da chave privada!!";
                throw new nfephpException($msg);
            }*/
            $priv_key = $this->priKEY;
            /*while (! feof($filep)) {
                $priv_key .= fread($filep, 8192);
            }
            fclose($filep);*/
            $pkeyid = openssl_get_privatekey($priv_key);
            //limpeza do xml com a retirada dos CR, LF e TAB
            $order = array("\r\n", "\n", "\r", "\t");
            $replace = '';
            $xml = str_replace($order, $replace, $xml);
            // Habilita a manipula�ao de erros da libxml
            libxml_use_internal_errors(true);
            //limpa erros anteriores que possam estar em mem�ria
            libxml_clear_errors();
            //carrega o documento DOM
            $xmldoc = new DOMDocument('1.0', 'utf-8');
            $xmldoc->preservWhiteSpace = false; //elimina espa�os em branco
            $xmldoc->formatOutput = false;
            //� muito importante deixar ativadas as op�oes para limpar os espacos em branco
            //e as tags vazias
            if ($xmldoc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $root = $xmldoc->documentElement;
            } else {
                $msg = "Erro ao carregar XML, provavel erro na passagem do par�metro docxml ou no pr�prio xml!!";
                $errors = libxml_get_errors();
                if (!empty($errors)) {
                    $countI = 1;
                    foreach ($errors as $error) {
                        $msg .= "\n  [$countI]-".trim($error->message);
                        $countI++;
                    }
                    libxml_clear_errors();
                }
                throw new nfephpException($msg);
            }
            //extrair a tag com os dados a serem assinados
            $node = $xmldoc->getElementsByTagName($tagid)->item(0);
            if (!isset($node)) {
                $msg = "A tag < $tagid > n�o existe no XML!!";
                throw new nfephpException($msg);
            }
            //extrai o atributo ID com o numero da NFe de 44 digitos
            $Id = $node->getAttribute("Id");
            //extrai e canoniza os dados da tag para uma string
            $dados = $node->C14N(false, false, null, null);
            //calcular o hash dos dados
            $hashValue = hash('sha1', $dados, true);
            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);
            //monta a tag da assinatura digital
            $Signature = $xmldoc->createElementNS($this->URLdsig, 'Signature');
            $root->appendChild($Signature);
            $SignedInfo = $xmldoc->createElement('SignedInfo');
            $Signature->appendChild($SignedInfo);
            //estabelece o m�todo de canoniza��o
            $newNode = $xmldoc->createElement('CanonicalizationMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLCanonMeth);
            //estabelece o m�todo de assinatura
            $newNode = $xmldoc->createElement('SignatureMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLSigMeth);
            //indica a referencia da assinatura
            $Reference = $xmldoc->createElement('Reference');
            $SignedInfo->appendChild($Reference);
            $Reference->setAttribute('URI', '#'.$Id);
            //estabelece as tranforma��es
            $Transforms = $xmldoc->createElement('Transforms');
            $Reference->appendChild($Transforms);
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);
            //estabelece o m�todo de calculo do hash
            $newNode = $xmldoc->createElement('DigestMethod');
            $Reference->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLDigestMeth);
            //carrega o valor do hash
            $newNode = $xmldoc->createElement('DigestValue', $digValue);
            $Reference->appendChild($newNode);
            //extrai e canoniza os dados a serem assinados para uma string
            $dados = $SignedInfo->C14N(false, false, null, null);
            //inicializa a variavel que ir� receber a assinatura
            $signature = '';
            //executa a assinatura digital usando o resource da chave privada
            openssl_sign($dados, $signature, $pkeyid);
            //codifica assinatura para o padr�o base64
            $signatureValue = base64_encode($signature);
            //insere o valor da assinatura digtal
            $newNode = $xmldoc->createElement('SignatureValue', $signatureValue);
            $Signature->appendChild($newNode);
            //insere a chave publica usada para conferencia da assinatura digital
            $KeyInfo = $xmldoc->createElement('KeyInfo');
            $Signature->appendChild($KeyInfo);
            //X509Data
            $X509Data = $xmldoc->createElement('X509Data');
            $KeyInfo->appendChild($X509Data);
            //carrega o certificado sem as tags de inicio e fim
            $cert = $this->pCleanCerts($this->pubKEY);
            //X509Certificate
            $newNode = $xmldoc->createElement('X509Certificate', $cert);
            $X509Data->appendChild($newNode);
            //grava em uma string o objeto DOM
            $xml = $xmldoc->saveXML();
            //libera a chave privada da memoria
            openssl_free_key($pkeyid);
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        //retorna o documento xml assinado
        return $xml;
    } //fim signXML

    /**
     * statusServico
     * Verifica o status do servi�o da SEFAZ/SVC
     *
     * $this->cStat = 107 - "Servi�o em Opera��o"
     *        cStat = 108 - "Servi�o Paralisado Momentaneamente (curto prazo)"
     *        cStat = 109 - "Servi�o Paralisado sem Previs�o"
     *        cStat = 113 - "SVC em processo de desativa��o. SVC ser� desabilitada
     *                       para a SEFAZ-XX em dd/mm/aa �s hh:mm horas"
     *        cStat = 114 - "SVC desabilitada pela SEFAZ Origem"
     *
     * @name statusServico
     * @param  string $siglaUF sigla da unidade da Federa��o
     * @param  integer $tpAmb tipo de ambiente 1-produ��o e 2-homologa��o
     * @param  array $aRetorno parametro passado por referencia contendo a resposta da consulta em um array
     * @return mixed string XML do retorno do webservice, ou false se ocorreu algum erro
     */
    public function statusServico($siglaUF = '', $tpAmb = '', &$aRetorno = array())
    {
        try {
            $this->errMsg = '';
            $this->errStatus = false;
            //retorno da fun�ao
            $aRetorno = array(
                'bStat'=>false,
                'tpAmb'=>'',
                'verAplic'=>'',
                'cUF'=>'',
                'cStat'=>'',
                'tMed'=>'',
                'dhRetorno'=>'',
                'dhRecbto'=>'',
                'xMotivo'=>'',
                'xObs'=>'');
            // caso o parametro tpAmb seja vazio
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            //define a sigla da UF, se vazia utiliza o atributo da classe Tools
            $siglaUF = $siglaUF == '' ? $this->siglaUF : $siglaUF;
            //busca o c�digo da UF a partir da sigla
            $cUF = $this->cUFlist[$siglaUF];
            //se contingencia SVCAN/SVCRS habilitada sobrescreve a sigla da UF, caso contr�rio
            //usa a pr�pria UF, logo abaixo ao carregar os webservices
            if ($this->enableSVCAN) {
                $siglaUF = self::CONTINGENCIA_SVCAN;
            } elseif ($this->enableSVCRS) {
                $siglaUF = self::CONTINGENCIA_SVCRS;
            }
            $aURL = $this->pLoadSEFAZ($tpAmb, $siglaUF);
            //identifica��o do servi�o
            $servico = 'NfeStatusServico';
            //recupera��o da vers�o
            $versao = $aURL[$servico]['version'];
            //recupera��o da url do servi�o
            $urlservico = $aURL[$servico]['URL'];
            //recupera��o do m�todo
            $metodo = $aURL[$servico]['method'];
            //recupera��o da opera��o
            $operation = $aURL[$servico]['operation'];
            //montagem do namespace do servi�o
            $namespace = $this->URLPortal.'/wsdl/'.$operation;
            //montagem do cabe�alho da comunica��o SOAP
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\">"
                    . "<cUF>$cUF</cUF>"
                    . "<versaoDados>$versao</versaoDados>"
                    . "</nfeCabecMsg>";
            //montagem dos dados da mensagem SOAP
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">"
                    . "<consStatServ xmlns=\"$this->URLPortal\" versao=\"$versao\">"
                    . "<tpAmb>$tpAmb</tpAmb>"
                    . "<cUF>$cUF</cUF>"
                    . "<xServ>STATUS</xServ>"
                    . "</consStatServ></nfeDadosMsg>";
            //consome o webservice e verifica o retorno do SOAP
            if (! $retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb)) {
                throw new nfephpException("Nao houve retorno Soap verifique a mensagem de erro e o debug!!");
            }
            //cria documento DOM a partir do retorno e trata dados de retorno
            $doc = new DomDocumentNFePHP($retorno);
            if (! $cStat = $this->pSimpleGetValue($doc, 'cStat')) {
                throw new nfephpException("N�o houve retorno Soap verifique a mensagem de erro e o debug!!");
            } elseif ($cStat == '107') { //107-servi�o em opera��o
                $aRetorno['bStat'] = true;
            }
            // tipo de ambiente
            $aRetorno['tpAmb'] = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            // vers�o do aplicativo
            $aRetorno['verAplic'] = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            // C�digo da UF que atendeu a solicita��o
            $aRetorno['cUF'] = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;
            // status do servi�o
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // tempo medio de resposta
            $aRetorno['tMed'] = $this->pSimpleGetValue($doc, 'tMed');
            // data e hora do retorno a opera��o (opcional)
            if ($dhRetorno = $this->pSimpleGetValue($doc, 'dhRetorno')) {
                $aRetorno['dhRetorno'] = date("d/m/Y H:i:s", $this->pConvertTime($dhRetorno));
            }
            // data e hora da mensagem (opcional)
            if ($dhRecbto = $this->pSimpleGetValue($doc, 'dhRecbto')) {
                $aRetorno['dhRecbto'] = date("d/m/Y H:i:s", $this->pConvertTime($dhRecbto));
            }
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = $this->pSimpleGetValue($doc, 'xMotivo');
            // oberva�oes (opcional)
            $aRetorno['xObs'] = $this->pSimpleGetValue($doc, 'xObs');
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $retorno;
    }

    /**
     * consultaCadastro
     * Solicita dados de situa�ao de Cadastro, somente funciona para
     * cadastros de empresas localizadas no mesmo estado do solicitante e os dados
     * retornados podem ser bastante incompletos. N�o � recomendado seu uso.
     *
     * @name consultaCadastro
     * @param  string  $siglaUF sigla da unidade da federa��o
     * @param   string  $IE opcional numero da inscri��o estadual
     * @param   string  $CNPJ opcional numero do cnpj
     * @param   string  $CPF opcional numero do cpf
     * @param   string  $tpAmb tipo de ambiente se n�o informado ser� usado o ambiente default
     * @return mixed false se falha ou array se retornada informa��o
     */
    public function consultaCadastro($siglaUF, $CNPJ = '', $IE = '', $CPF = '', $tpAmb = '')
    {
        //variavel de retorno do metodo
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dados'=>array());
        $flagIE = false;
        $flagCNPJ = false;
        $flagCPF = false;
        $marca = '';
        //selecionar o criterio de filtragem CNPJ ou IE ou CPF
        if ($CNPJ != '') {
            $flagCNPJ = true;
            $marca = 'CNPJ-'.$CNPJ;
            $filtro = "<CNPJ>".$CNPJ."</CNPJ>";
            $CPF = '';
            $IE = '';
        }
        if ($IE != '') {
            $flagIE = true;
            $marca = 'IE-'.$IE;
            $filtro = "<IE>".$IE."</IE>";
            $CNPJ = '';
            $CPF = '';
        }
        if ($CPF != '') {
            $flagCPF = true;
            $filtro = "<CPF>".$CPF."</CPF>";
            $marca = 'CPF-'.$CPF;
            $CNPJ = '';
            $IE = '';
        }
        //se nenhum crit�rio � satisfeito
        if (!($flagIE || $flagCNPJ || $flagCPF)) {
            //erro nao foi passado parametro de filtragem
            $msg = "Pelo menos uma e somente uma op��o deve ser indicada CNPJ, CPF ou IE !!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        if ($tpAmb == '') {
            $tpAmb = $this->tpAmb;
        }
        //carrega as URLs
        $aURL = $this->aURL;
        // caso a sigla do estado seja diferente do emitente ou o ambiente seja diferente
        if ($siglaUF != $this->siglaUF || $tpAmb != $this->tpAmb) {
            //recarrega as url referentes aos dados passados como parametros para a fun��o
            $aURL = $this->pLoadSEFAZ($tpAmb, $siglaUF);
        }
        //busca o c�digo da UF a partir da sigla
        $cUF = $this->cUFlist[$siglaUF];
        //identifica��o do servi�o
        $servico = 'NfeConsultaCadastro';
        //recupera��o da vers�o
        $versao = $aURL[$servico]['version'];
        //recupera��o da url do servi�o
        $urlservico = $aURL[$servico]['URL'];
        //recupera��o do m�todo
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do servi�o
        $operation = $aURL[$servico]['operation'];
        $namespace = $this->URLPortal.'/wsdl/'.$operation;
        if ($urlservico=='') {
            $msg = "Este servi�o n�o est� dispon�vel para a SEFAZ $siglaUF!!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //montagem do cabe�alho da comunica��o SOAP
        $cabec = "<nfeCabecMsg xmlns=\"$namespace\">"
                . "<cUF>$cUF</cUF>"
                . "<versaoDados>$versao</versaoDados>"
                . "</nfeCabecMsg>";
        //montagem dos dados da mensagem SOAP
        $dados = "<nfeDadosMsg xmlns=\"$namespace\">"
                . "<ConsCad xmlns=\"$this->URLnfe\" versao=\"$versao\">"
                . "<infCons>"
                . "<xServ>CONS-CAD</xServ>"
                . "<UF>$siglaUF</UF>$filtro</infCons>"
                . "</ConsCad></nfeDadosMsg>";
        //envia a solicita��o via SOAP
        $retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
        //verifica o retorno
        if (!$retorno) {
            //n�o houve retorno
            $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //tratar dados de retorno
        $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $infCons = $doc->getElementsByTagName('infCons')->item(0);
        $cStat = !empty($infCons->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                $infCons->getElementsByTagName('cStat')->item(0)->nodeValue : '';
        $xMotivo = !empty($infCons->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                $infCons->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
        $infCad = $infCons->getElementsByTagName('infCad');
        if ($cStat == '') {
            //houve erro
            $msg = "cStat est� em branco, houve erro na comunica��o Soap verifique a mensagem de erro e o debug!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //tratar erro 239 Vers�o do arquivo XML n�o suportada
        if ($cStat == '239') {
            $this->pTrata239($retorno, $this->siglaUF, $tpAmb, $servico, $versao);
            $msg = "Vers�o do arquivo XML n�o suportada!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        if ($cStat != '111') {
            $msg = "Retorno de ERRO: $cStat - $xMotivo";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }

        $aCad = array();
        if (isset($infCad)) {
            $aRetorno['bStat'] = true;
            //existem dados do cadastro e podem ser multiplos
            $countI = 0;
            foreach ($infCad as $dCad) {
                $ender = $dCad->getElementsByTagName('ender')->item(0);
                $aCad[$countI]['CNPJ'] = !empty($dCad->getElementsByTagName('CNPJ')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('CNPJ')->item(0)->nodeValue : '';
                $aCad[$countI]['CPF'] = !empty($dCad->getElementsByTagName('CPF')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('CPF')->item(0)->nodeValue : '';
                $aCad[$countI]['IE'] = !empty($dCad->getElementsByTagName('IE')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('IE')->item(0)->nodeValue : '';
                $aCad[$countI]['UF'] = !empty($dCad->getElementsByTagName('UF')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('UF')->item(0)->nodeValue : '';
                $aCad[$countI]['cSit'] = !empty($dCad->getElementsByTagName('cSit')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('cSit')->item(0)->nodeValue : '';
                $aCad[$countI]['indCredNFe'] = !empty($dCad->getElementsByTagName('indCredNFe')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('indCredNFe')->item(0)->nodeValue : '';
                $aCad[$countI]['indCredCTe'] = !empty($dCad->getElementsByTagName('indCredCTe')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('indCredCTe')->item(0)->nodeValue : '';
                $aCad[$countI]['xNome'] = !empty($dCad->getElementsByTagName('xNome')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('xNome')->item(0)->nodeValue : '';
                $aCad[$countI]['xRegApur'] = !empty($dCad->getElementsByTagName('xRegApur')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('xRegApur')->item(0)->nodeValue : '';
                $aCad[$countI]['CNAE'] = !empty($dCad->getElementsByTagName('CNAE')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('CNAE')->item($i)->nodeValue : '';
                $aCad[$countI]['dIniAtiv'] = !empty($dCad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue : '';
                $aCad[$countI]['dUltSit'] = !empty($dCad->getElementsByTagName('dUltSit')->item(0)->nodeValue) ?
                        $dCad->getElementsByTagName('dUltSit')->item(0)->nodeValue : '';
                if (isset($ender)) {
                    $aCad[$countI]['xLgr'] = !empty($ender->getElementsByTagName('xLgr')->item(0)->nodeValue) ?
                            $ender->getElementsByTagName('xLgr')->item(0)->nodeValue : '';
                    $aCad[$countI]['nro'] = !empty($ender->getElementsByTagName('nro')->item(0)->nodeValue) ?
                            $ender->getElementsByTagName('nro')->item(0)->nodeValue : '';
                    $aCad[$countI]['xCpl'] = !empty($ender->getElementsByTagName('xCpl')->item(0)->nodeValue) ?
                            $ender->getElementsByTagName('xCpl')->item(0)->nodeValue : '';
                    $aCad[$countI]['xBairro'] = !empty($ender->getElementsByTagName('xBairro')->item(0)->nodeValue) ?
                            $ender->getElementsByTagName('xBairro')->item(0)->nodeValue : '';
                    $aCad[$countI]['cMun'] = !empty($ender->getElementsByTagName('cMun')->item(0)->nodeValue) ?
                            $ender->getElementsByTagName('cMun')->item(0)->nodeValue : '';
                    $aCad[$countI]['xMun'] = !empty($ender->getElementsByTagName('xMun')->item(0)->nodeValue) ?
                            $ender->getElementsByTagName('xMun')->item(0)->nodeValue : '';
                    $aCad[$countI]['CEP'] = !empty($ender->getElementsByTagName('CEP')->item(0)->nodeValue) ?
                            $ender->getElementsByTagName('CEP')->item(0)->nodeValue : '';
                }
                $countI++;
            } //fim foreach
        }
        $aRetorno['cStat'] = $cStat;
        $aRetorno['xMotivo'] = $xMotivo;
        $aRetorno['dados'] = $aCad;
        return $aRetorno;
    } //fim consultaCadastro

    /**
     * autoriza
     * Envia NFe para a SEFAZ autorizar.
     * ATEN��O! Este � o antigo m�todo "sendLot()" que enviava lotes de NF-e vers�o "2.00"
     * consumindo o WS "NfeRecepcao2", agora este m�todo est� preparado apenas para a vers�o
     * "3.10" e por isso utiliza o WS "NfeAutorizacao" sempre em modo s�ncrono.
     *
     * @name autoriza
     * @package NFePHP
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string  $sxml   string com uma nota fiscal em xml
     * @param integer $idLote id do lote e um numero (numera��o sequencial)
     * @param array   $aRetorno parametro passado por referencia contendo a resposta da consulta em um array
     * @param integer $indSinc Indica��o webservice ass�ncrono (0) ou s�ncrono (1)
     * @return mixed string XML do retorno do webservice, ou false se ocorreu algum erro
     */
    public function autoriza($sxml, $idLote, &$aRetorno = array(), $indSinc = 0)
    {
        try {
            //retorno do m�todo em array (esta estrutura espelha a estrutura do XML retornado pelo webservice
            //IMPORTANTE: esta estrutura varia parcialmente conforme o $indSinc
            $aRetorno = array(
                'bStat'=>false,
                'tpAmb'=>'',
                'verAplic'=>'',
                'cStat'=>'',
                'xMotivo'=>'',
                'cUF'=>'',
                'dhRecbto'=>'');
            if ($indSinc === 0) {
                //dados do recibo do lote (gerado apenas se o lote for aceito)
                $aRetorno['infRec'] = array('nRec'=>'','tMed'=>'');
            } elseif ($indSinc === 1) {
                //dados do protocolo de recebimento da NF-e
                $aRetorno['protNFe'] = array(
                    'versao'=>'',
                    'infProt'=>array( //informa��es do protocolo de autoriza��o da NF-e
                        'tpAmb'=>'',
                        'verAplic'=>'',
                        'chNFe'=>'',
                        'dhRecbto'=>'',
                        'nProt'=>'',
                        'digVal'=>'',
                        'cStat'=>'',
                        'xMotivo'=>''));
            } else {
                throw new nfephpException("Parametro indSinc deve ser inteiro 0 ou 1, verifique!!");
            }
            //verifica se alguma SVC esta habilitada, neste caso precisa recarregar os webservices
            if ($this->enableSVCAN) {
                $aURL = $this->pLoadSEFAZ($this->tpAmb, self::CONTINGENCIA_SVCAN);
            } elseif ($this->enableSVCRS) {
                $aURL = $this->pLoadSEFAZ($this->tpAmb, self::CONTINGENCIA_SVCRS);
            } else {
                $aURL = $this->aURL;
            }
            //identifica��o do servi�o: autoriza��o de NF-e
            $servico = 'NfeAutorizacao';
            //recupera��o da vers�o
            $versao = $aURL[$servico]['version'];
            //recupera��o da url do servi�o
            $urlservico = $aURL[$servico]['URL'];
            //recupera��o do m�todo
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do servi�o
            $operation = $aURL[$servico]['operation'];
            $namespace = $this->URLPortal.'/wsdl/'.$operation;
            //valida o par�metro da string do XML da NF-e
            if (empty($sxml) || ! simplexml_load_string($sxml)) {
                throw new nfephpException("XML de NF-e para autorizacao "
                        . "recebido no parametro parece invalido, verifique");
            }
            // limpa a variavel
            $sNFe = $sxml;
            //remove <?xml version="1.0" encoding=... e demais caracteres indesejados
            $sNFe = preg_replace("/<\?xml.*\?>/", "", $sNFe);
            $sNFe = str_replace(array("\r","\n","\s"), "", $sNFe);
            //montagem do cabe�alho da comunica��o SOAP
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\">"
                    . "<cUF>$this->cUF</cUF>"
                    . "<versaoDados>$versao</versaoDados>"
                    . "</nfeCabecMsg>";
            //montagem dos dados da mensagem SOAP
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">"
                    . "<enviNFe xmlns=\"$this->URLPortal\" versao=\"$versao\">"
                    . "<idLote>$idLote</idLote>"
                    . "<indSinc>$indSinc</indSinc>$sNFe</enviNFe></nfeDadosMsg>";
            //envia dados via SOAP
            $retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
            //verifica o retorno
            if (! $retorno) {
                throw new nfephpException("Nao houve retorno Soap verifique a mensagem de erro e o debug!!");
            }
            //tratar dados de retorno
            $doc = new DomDocumentNFePHP();
            $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = $this->pSimpleGetValue($doc, "cStat");
            $xMotivo = $this->pSimpleGetValue($doc, "xMotivo");
            //verifica o codigo do status da resposta, se vazio houve erro
            if ($cStat == '') {
                throw new nfephpException("O retorno nao contem cStat verifique o debug do soap !!");
            } elseif ($indSinc === 0 && $cStat == '103') { //103-Lote recebido com sucesso
                $aRetorno['bStat'] = true;
            } elseif ($indSinc === 1 && $cStat == '104') { //104-Lote processado, podendo ter ou n�o o protNFe (#AR11 no layout)
                $aRetorno['bStat'] = true;
            } else {
                throw new nfephpException(sprintf("%s - %s", $cStat, $xMotivo));
            }
            // status da resposta do webservice
            $aRetorno['cStat'] = $cStat;
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = $this->pSimpleGetValue($doc, "xMotivo");
            // data e hora da mensagem (opcional)
            if ($dhRecbto = $this->pSimpleGetValue($doc, "dhRecbto")) {
                $aRetorno['dhRecbto'] = date("d/m/Y H:i:s", $this->pConvertTime($dhRecbto));
            }
            //tipo do ambiente, vers�o do aplicativo e c�digo da UF
            $aRetorno['tpAmb'] = $this->pSimpleGetValue($doc, "tpAmb");
            $aRetorno['verAplic'] = $this->pSimpleGetValue($doc, "verAplic");
            $aRetorno['cUF'] = $this->pSimpleGetValue($doc, "cUF");
            if ($indSinc == 1) {
                //retorno s�ncrono do webservice: dados do protocolo da NF-e
                $nodeProtNFe = $doc->getElementsByTagName('protNFe')->item(0);
                $nodeInfProt = $doc->getElementsByTagName('infProt')->item(0);
                $aRetorno['protNFe']['versao'] = $nodeProtNFe->getAttribute('versao');
                $infProt = array();
                $infProt['tpAmb'] = $this->pSimpleGetValue($nodeInfProt, "tpAmb");
                $infProt['verAplic'] = $this->pSimpleGetValue($nodeInfProt, "verAplic");
                $infProt['chNFe'] = $this->pSimpleGetValue($nodeInfProt, "chNFe");
                $dhRecbto = $this->pSimpleGetValue($nodeInfProt, "dhRecbto");
                $infProt['dhRecbto'] = date("d/m/Y H:i:s", $this->pConvertTime($dhRecbto));
                $infProt['digVal'] = $this->pSimpleGetValue($nodeInfProt, "digVal");
                $infProt['cStat'] = $this->pSimpleGetValue($nodeInfProt, "cStat");
                $infProt['xMotivo'] = $this->pSimpleGetValue($nodeInfProt, "xMotivo");
                //n�mero do protocolo de autoriza��o (opcional)
                $infProt['nProt'] = $this->pSimpleGetValue($nodeInfProt, "nProt");
                $aRetorno['protNFe']['infProt'] = $infProt;
                //nome do arquivo de retorno: chave da NF-e com sufixo "-prot"
                $nome = $this->temDir.$infProt['chNFe'].'-prot.xml';
            } else {
                //retorno ass�ncrono do webservice: dados do recibo do lote
                $aRetorno['infRec'] = array();
                $aRetorno['infRec']['nRec'] = $this->pSimpleGetValue($doc, "nRec");
                $aRetorno['infRec']['tMed'] = $this->pSimpleGetValue($doc, "tMed");
                //nome do arquivo de retorno: ID do lote com sufixo "-prot"
                $nome = $this->temDir.$idLote.'-rec.xml';
            }
            //grava o retorno na pasta de tempor�rios
            $nome = $doc->save($nome);
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $retorno;
    }// fim autoriza

    /**
     * getProtocol
     * Solicita resposta do lote de Notas Fiscais ou o protocolo de
     * autoriza��o da NFe
     * Caso $this->cStat == 105 Tentar novamente mais tarde
     *
     * @name getProtocol
     * @param  string   $recibo numero do recibo do envio do lote
     * @param  string   $chave  numero da chave da NFe de 44 digitos
     * @param   string   $tpAmb  numero do ambiente 1-producao e 2-homologa��o
     * @param   array    $aRetorno Array com os dados do protocolo
     * @return mixed    false ou xml do retorno do webservice
     */
    public function getProtocol($recibo = '', $chave = '', $tpAmb = '', &$aRetorno = array())
    {
        try {
            //carrega defaults do array de retorno
            $aRetorno = array(
                'bStat'=>false,
                'verAplic'=>'',
                'cStat'=>'',
                'xMotivo'=>'',
                'cUF'=>'',
                'chNFe'=>'',
                'aProt'=>'',
                'aCanc'=>'',
                'xmlRetorno'=>'');
            $cUF = $this->cUF;
            $siglaUF = $this->siglaUF;
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if (!in_array($tpAmb, array(self::AMBIENTE_PRODUCAO, self::AMBIENTE_HOMOLOGACAO))) {
                $tpAmb = self::AMBIENTE_HOMOLOGACAO;
            }
            $aURL = $this->aURL;
            $ctpEmissao = '';
            //verifica se a chave foi passada
            if ($chave != '') {
                //se sim extrair o cUF da chave
                $cUF = substr($chave, 0, 2);
                $ctpEmissao = substr($chave, 34, 1);
                //testar para ver se � o mesmo do emitente
                if ($cUF != $this->cUF || $tpAmb != $this->tpAmb) {
                    //se n�o for o mesmo carregar a sigla
                    $siglaUF = $this->siglaUFList[$cUF];
                    //recarrega as url referentes aos dados passados como parametros para a fun��o
                    $aURL = $this->pLoadSEFAZ($tpAmb, $siglaUF);
                }
            }
            //verifica se alguma SVC esta habilitada
            if ($this->enableSVCAN) {
                $aURL = $this->pLoadSEFAZ($tpAmb, self::CONTINGENCIA_SVCAN);
            } elseif ($this->enableSVCRS) {
                $aURL = $this->pLoadSEFAZ($tpAmb, self::CONTINGENCIA_SVCRS);
            }
            if ($recibo == '' && $chave == '') {
                throw new nfephpException("ERRO. Favor indicar o numero do "
                        . "recibo ou a chave de acesso da NF-e!");
            }
            if ($recibo != '' && $chave != '') {
                throw new nfephpException("ERRO. Favor indicar somente o "
                        . "numero do recibo ou a chave de acesso da NF-e!");
            }
            //consulta pelo recibo
            if ($recibo != '' && $chave == '') {
                //buscar os protocolos pelo numero do recibo do lote
                //identifica��o do servi�o
                $servico = 'NfeRetAutorizacao';
                //recupera��o da vers�o
                $versao = $aURL[$servico]['version'];
                //recupera��o da url do servi�o
                $urlservico = $aURL[$servico]['URL'];
                //recupera��o do m�todo
                $metodo = $aURL[$servico]['method'];
                //montagem do namespace do servi�o
                $operation = $aURL[$servico]['operation'];
                $namespace = $this->URLPortal.'/wsdl/'.$operation;
                //montagem do cabe�alho da comunica��o SOAP
                $cabec = "<nfeCabecMsg xmlns=\"$namespace\">"
                        . "<cUF>$cUF</cUF>"
                        . "<versaoDados>$versao</versaoDados>"
                        . "</nfeCabecMsg>";
                //montagem dos dados da mensagem SOAP
                $dados = "<nfeDadosMsg xmlns=\"$namespace\">"
                        . "<consReciNFe xmlns=\"$this->URLPortal\" versao=\"$versao\">"
                        . "<tpAmb>$tpAmb</tpAmb>"
                        . "<nRec>$recibo</nRec>"
                        . "</consReciNFe>"
                        . "</nfeDadosMsg>";
                //nome do arquivo
                $nomeArq = $recibo.'-protrec.xml';
            }
            //consulta pela chave
            if ($recibo == '' && $chave != '') {
                //buscar o protocolo pelo numero da chave de acesso
                //identifica��o do servi�o
                $servico = 'NfeConsultaProtocolo';
                //recupera��o da vers�o
                $versao = $aURL[$servico]['version'];
                //recupera��o da url do servi�o
                $urlservico = $aURL[$servico]['URL'];
                //recupera��o do m�todo
                $metodo = $aURL[$servico]['method'];
                //montagem do namespace do servi�o
                $operation = $aURL[$servico]['operation'];
                $namespace = $this->URLPortal.'/wsdl/'.$operation;
                //montagem do cabe�alho da comunica��o SOAP
                $cabec = "<nfeCabecMsg xmlns=\"$namespace\">"
                        . "<cUF>$cUF</cUF>"
                        . "<versaoDados>$versao</versaoDados>"
                        . "</nfeCabecMsg>";
                //montagem dos dados da mensagem SOAP
                $dados = "<nfeDadosMsg xmlns=\"$namespace\">"
                        . "<consSitNFe xmlns=\"$this->URLPortal\" versao=\"$versao\">"
                        . "<tpAmb>$tpAmb</tpAmb>"
                        . "<xServ>CONSULTAR</xServ>"
                        . "<chNFe>$chave</chNFe>"
                        . "</consSitNFe></nfeDadosMsg>";
            }
            //envia a solicita��o via SOAP
            if (!$retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb)) {
                throw new nfephpException("Nao houve retorno Soap verifique a mensagem de erro e o debug!");
            }
            //tratar dados de retorno
            $doc = new DomDocumentNFePHP($retorno);
            $cStat = $this->pSimpleGetValue($doc, "cStat");
            //verifica se houve erro no c�digo do status
            if ($cStat == '') {
                throw new nfephpException("Erro inesperado, cStat esta vazio!");
            }
            $envelopeBodyNode = $doc->getElementsByTagNameNS('http://www.w3.org/2003/05/soap-envelope', 'Body')->item(0)->childNodes->item(0);
            //Disponibiliza o conte�do xml do pacote de resposta (soap:Body) atrav�s do array de retorno
            $aRetorno['xmlRetorno'] = $doc->saveXML($envelopeBodyNode);
            //o retorno vai variar se for buscado o protocolo ou recibo
            //Retorno da consulta pela Chave da NF-e
            //retConsSitNFe 100 aceita 110 denegada 101 cancelada ou outro recusada
            // cStat xMotivo cUF chNFe protNFe retCancNFe
            if ($chave != '') {
                $aRetorno['bStat'] = true;
                $aRetorno['verAplic'] = $this->pSimpleGetValue($doc, 'verAplic');
                $aRetorno['cStat'] = $this->pSimpleGetValue($doc, 'cStat');
                $aRetorno['xMotivo'] = $this->pSimpleGetValue($doc, 'xMotivo');
                $aRetorno['cUF'] = $this->pSimpleGetValue($doc, 'cUF');
                $aRetorno['chNFe'] = $this->pSimpleGetValue($doc, 'chNFe');
                $infProt = $doc->getElementsByTagName('infProt')->item(0);
                $infCanc = $doc->getElementsByTagName('infCanc')->item(0);
                $procEventoNFe = $doc->getElementsByTagName('procEventoNFe');
                $aProt = array();
                if (isset($infProt)) {
                    foreach ($infProt->childNodes as $tnodes) {
                        $aProt[$tnodes->nodeName] = $tnodes->nodeValue;
                    }
                    if (!empty($aProt['dhRecbto'])) {
                        $aProt['dhRecbto'] = date("d/m/Y H:i:s", $this->pConvertTime($aProt['dhRecbto']));
                    } else {
                        $aProt['dhRecbto'] = '';
                    }
                    $aProt['xEvento'] = 'Autoriza��o';
                }
                $aCanc = '';
                if (isset($infCanc)) {
                    foreach ($infCanc->childNodes as $tnodes) {
                        $aCanc[$tnodes->nodeName] = $tnodes->nodeValue;
                    }
                    if (!empty($aCanc['dhRecbto'])) {
                        $aCanc['dhRecbto'] = date("d/m/Y H:i:s", $this->pConvertTime($aCanc['dhRecbto']));
                    } else {
                        $aCanc['dhRecbto'] = '';
                    }
                    $aCanc['xEvento'] = 'Cancelamento';
                }
                $aEventos = '';
                if (! empty($procEventoNFe)) {
                    foreach ($procEventoNFe as $kEli => $evento) {
                        $infEvento = $evento->getElementsByTagName('infEvento');
                        foreach ($infEvento as $iE) {
                            if ($iE->getElementsByTagName('detEvento')->item(0) != "") {
                                continue;
                            }
                            foreach ($iE->childNodes as $tnodes) {
                                $aEventos[$kEli][$tnodes->nodeName] = $tnodes->nodeValue;
                            }
                            $aEventos[$kEli]['dhRegEvento'] = date("d/m/Y H:i:s", $this->pConvertTime($aEventos[$kEli]['dhRegEvento']));
                        }
                    }
                }
                $aRetorno['aProt'] = $aProt;
                $aRetorno['aCanc'] = $aCanc;
                $aRetorno['aEventos'] = $aEventos;
                //gravar o retorno na pasta temp apenas se a nota foi aprovada ou denegada
                if (in_array($aRetorno['cStat'], array('100', '101', '110', '301', '302'))) {
                    //nome do arquivo
                    $nomeArq = $chave.'-prot.xml';
                    $nome = $this->temDir.$nomeArq;
                    $nome = $doc->save($nome);
                }
            }
            //Retorno da consulta pelo recibo
            //NFeRetRecepcao 104 tem retornos
            //nRec cStat xMotivo cUF cMsg xMsg protNfe* infProt chNFe dhRecbto nProt cStat xMotivo
            if ($recibo != '') {
                $countI = 0;
                $aRetorno['bStat'] = true;
                // status do servi�o
                $aRetorno['cStat'] = $this->pSimpleGetValue($doc, 'cStat');
                // motivo da resposta (opcional)
                $aRetorno['xMotivo'] = $this->pSimpleGetValue($doc, 'xMotivo');
                // numero do recibo consultado
                $aRetorno['nRec'] = $this->pSimpleGetValue($doc, 'nRec');
                // tipo de ambiente
                $aRetorno['tpAmb'] = $this->pSimpleGetValue($doc, 'tpAmb');
                // versao do aplicativo que recebeu a consulta
                $aRetorno['verAplic'] = $this->pSimpleGetValue($doc, 'verAplic');
                // codigo da UF que atendeu a solicitacao
                $aRetorno['cUF'] = $this->pSimpleGetValue($doc, 'cUF');
                // codigo da mensagem da SEFAZ para o emissor (opcional)
                $aRetorno['cMsg'] = $this->pSimpleGetValue($doc, 'cMsg');
                // texto da mensagem da SEFAZ para o emissor (opcional)
                $aRetorno['xMsg'] = $this->pSimpleGetValue($doc, 'xMsg');
                if ($cStat == '104') {
                    //aqui podem ter varios retornos dependendo do numero de NFe enviadas no Lote e j� processadas
                    $protNfe = $doc->getElementsByTagName('protNFe');
                    foreach ($protNfe as $d) {
                        $infProt = $d->getElementsByTagName('infProt')->item(0);
                        $protcStat = $infProt->getElementsByTagName('cStat')->item(0)->nodeValue;
                        //pegar os dados do protolo para retornar
                        foreach ($infProt->childNodes as $tnode) {
                           $aProt[$countI][$tnode->nodeName] = $tnode->nodeValue;
                        }
                        $countI++;
                        //incluido increment para controlador de indice do array
                        //salvar o protocolo somente se a nota estiver approvada ou denegada
                        if (in_array($protcStat, array('100', '110', '301', '302'))) {
                            $nomeprot = $this->temDir.$infProt->getElementsByTagName('chNFe')->item(0)->nodeValue.'-prot.xml';//id da nfe
                            //salvar o protocolo em arquivo
                            $novoprot = new DomDocumentNFePHP();
                            $pNFe = $novoprot->createElement("protNFe");
                            $pNFe->setAttribute("versao", "3.10");
                            // Importa o node e todo o seu conteudo
                            $node = $novoprot->importNode($infProt, true);
                            // acrescenta ao node principal
                            $pNFe->appendChild($node);
                            $novoprot->appendChild($pNFe);
                            $xml = $novoprot->saveXML();
                            $xml = str_replace(
                                '<?xml version="1.0" encoding="UTF-8  standalone="no"?>',
                                '<?xml version="1.0" encoding="UTF-8"?>',
                                $xml
                            );
                            $xml = str_replace(array("default:",":default","\r","\n","\s"), "", $xml);
                            $xml = str_replace("  ", " ", $xml);
                            $xml = str_replace("  ", " ", $xml);
                            $xml = str_replace("  ", " ", $xml);
                            $xml = str_replace("  ", " ", $xml);
                            $xml = str_replace("  ", " ", $xml);
                            $xml = str_replace("> <", "><", $xml);
                            file_put_contents($nomeprot, $xml);
                        } //fim protcSat
                    } //fim foreach
                }//fim cStat
                //converter o hor�rio do recebimento retornado pela SEFAZ em formato padr�o
                if (isset($aProt)) {
                    foreach ($aProt as &$p) {
                        $p['dhRecbto'] = !empty($p['dhRecbto']) ?
                            date(
                                "d/m/Y H:i:s",
                                $this->pConvertTime($p['dhRecbto'])
                            ) : '';
                    }
                } else {
                    $aProt = array();
                }
                $aRetorno['aProt'] = $aProt; //passa o valor de $aProt para o array de retorno
                $nomeArq = $recibo.'-recprot.xml';
                $nome = $this->temDir.$nomeArq;
                $nome = $doc->save($nome);
            } //fim recibo
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }//fim catch
        return $retorno;
    } //fim getProtocol

    /**
     * getDistDFe
     * Servi�o destinado � distribui��o de informa��es resumidas e documentos
     * fiscais eletr�nicos de interesse de um ator, seja este pessoa
     * f�sica ou jur�dica.
     *
     * Este servi�o � oferecido apenas no AN - Ambiente Nacional
     * Web Service de Consulta da Rela��o de Documentos Destinados
     * este m�todo ir� substituir o m�todo getListNFe
     *
     * @param string $fonte Deve ser a sigla da UF; n�o usar AN pois n�o existe AN em $this->cUFlist
     * @param string $tpAmb
     * @param integer $ultNSU
     * @param integer $numNSU
     * @param array $resp
     * @param boolean $descompactar
     * @return mixed boolean ou string
     * @throws nfephpException
     */
    public function getDistDFe(
        $fonte = 'XX',
        $tpAmb = '2',
        $cnpj = '',
        $ultNSU = 0,
        $numNSU = 0,
        &$resp = array(),
        $descompactar = false
    ) {
        $resp = array(
            'bStat' => false,
            'cStat' => 0,
            'xMotivo' => '',
            'dhResp' => '',
            'ultNSU' => 0,
            'maxNSU' => 0,
            'docs' => array()
        );
        try {
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if ($cnpj == '') {
                $cnpj = $this->cnpj;
            }
            //montagem do namespace do servi�o
            $servico = 'NFeDistribuicaoDFe';
            //carrega servi�o
            $this->pLoadServico(
                $servico,
                $fonte,
                $tpAmb,
                $cUF,
                $urlservico,
                $namespace,
                $cabec,
                $metodo,
                $versao
            );
            $ultNSU = str_pad($ultNSU, 15, '0', STR_PAD_LEFT);
            $tagNSU = "<distNSU><ultNSU>$ultNSU</ultNSU></distNSU>";
            if ($numNSU != 0) {
                $numNSU = str_pad($numNSU, 15, '0', STR_PAD_LEFT);
                $tagNSU = "<consNSU><NSU>$numNSU</NSU></consNSU>";
            }
            //monta a consulta
            $cons = "<distDFeInt xmlns=\"$this->URLPortal\" versao=\"$versao\">"
                    . "<tpAmb>$tpAmb</tpAmb>"
                    . "<cUFAutor>$this->cUF</cUFAutor>"
                    . "<CNPJ>$cnpj</CNPJ>$tagNSU</distDFeInt>";
            //montagem dos dados da mensagem SOAP
            $dados = "<nfeDistDFeInteresse xmlns=\"$namespace\">"
                    . "<nfeDadosMsg xmlns=\"$namespace\">$cons</nfeDadosMsg>"
                    . "</nfeDistDFeInteresse>";
            //grava solicita��o em temp
            $tipoNSU = (int) ($numNSU != 0 ? $numNSU : $ultNSU);
            $datahora = date('Ymd_His');
            if (! file_put_contents($this->temDir."$tipoNSU-$datahora-consDFe.xml", $cons)) {
                throw new nfephpException("Falha na gravacao do arquivo DFe de entrada!");
            }
            //envia dados via SOAP e verifica o retorno este webservice n�o requer cabe�alho
            $cabec = "";
            if (!$retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb)) {
                throw new nfephpException("Nao houve retorno Soap verifique a mensagem de erro e o debug!!");
            }
            file_put_contents($this->temDir."$tipoNSU-$datahora-retDFe.xml", $retorno);
            //tratar dados de retorno
            $dom = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $dom->formatOutput = false;
            $dom->preserveWhiteSpace = false;
            $dom->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retDistDFeInt = $dom->getElementsByTagName("retDistDFeInt")->item(0);
            $cStat = ! empty($dom->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = ! empty($dom->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat == '') {
                //houve erro
                $msg = "cStat est� em branco, houve erro na comunica��o Soap "
                        . "verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            $bStat = true;
            $dhResp = ! empty($dom->getElementsByTagName('dhResp')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('dhResp')->item(0)->nodeValue : '';
            $ultNSU = ! empty($dom->getElementsByTagName('ultNSU')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('ultNSU')->item(0)->nodeValue : '';
            $maxNSU = ! empty($dom->getElementsByTagName('maxNSU')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('maxNSU')->item(0)->nodeValue : '';
            $resp = array(
                'bStat' => $bStat,
                'cStat' => (int) $cStat,
                'xMotivo' => (string) $xMotivo,
                'dhResp' => (string) $dhResp,
                'ultNSU' => (int) $ultNSU,
                'maxNSU' => (int) $maxNSU,
                'docs' => array()
            );
            if ($cStat != '138') {
                return $retorno;
            }
            //se cStat == 138 ent�o existem docs
            $docs = $dom->getElementsByTagName('docZip');
            foreach ($docs as $doc) {
                $nsu = (int) $doc->getAttribute('NSU');
                $schema = (string) $doc->getAttribute('schema');
                //o conteudo desse dado � um zip em base64
                //para deszipar deve primeiro descomverter de base64
                //e depois aplicar a descompacta��o
                $zip = (string) $doc->nodeValue;
                if ($descompactar) {
                    $zipdata = base64_decode($zip);
                    $zip = $this->pGunzip1($zipdata);
                }
                $aDocs[] = array(
                    'NSU' => $nsu,
                    'schema' => $schema,
                    'dados' => $zip
                );
            }
            $resp['docs'] = $aDocs;
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }//fim catch
        return $retorno;
    }//fim getDistDFe

    /**
     * getListNFe
     * Consulta da Rela��o de Documentos Destinados
     * para um determinado CNPJ de destinat�rio informado na NF-e.
     *
     * Este servi�o n�o suporta SVC !!!
     *
     * @name getListNFe
     * @param boolean $ambNac TRUE - usa ambiente Nacional para buscar a lista de NFe, FALSE usa sua pr�pria SEFAZ
     * @param string $indNFe Indicador de NF-e consultada:
     * 0=Todas as NF-e; 1=Somente as NF-e que ainda n�o tiveram manifesta��o do destinat�rio
     * (Desconhecimento da opera��o, Opera��o n�o Realizada ou Confirma��o da Opera��o);
     * 2=Idem anterior, incluindo as NF-e que tamb�m n�o tiveram a Ci�ncia da Opera��o
     * @param string $indEmi Indicador do Emissor da NF-e:
     * 0=Todos os Emitentes / Remetentes; 1=Somente as NF-e emitidas por emissores / remetentes
     * que n�o tenham a mesma raiz do CNPJ do destinat�rio (para excluir as notas fiscais
     * de transfer�ncia entre filiais).
     * @param string $ultNSU �ltimo NSU recebido pela Empresa.
     * Caso seja informado com zero, ou com um NSU muito antigo, a consulta
     * retornar� unicamente as notas fiscais que tenham sido recepcionadas
     * nos �ltimos 15 dias.
     * @param string $tpAmb Tipo de ambiente 1=Produ��o 2=Homologa��o
     * @param array $resp Array com os retornos parametro passado por REFERENCIA
     * @return mixed False ou xml com os dados
     */
    public function getListNFe($ambNac = true, $indNFe = '0', $indEmi = '0', $ultNSU = '', $tpAmb = '', &$resp = array())
    {
        try {
            $datahora = date('Ymd_His');
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if (!$ambNac) {
                $sigla = $this->siglaUF;
            } else {
                $sigla = 'AN';
            }
            $aURL = $this->pLoadSEFAZ($tpAmb, $sigla);
            if ($ultNSU == '') {
                //buscar o �ltimo NSU no xml
                $ultNSU = $this->pGetUltNSU($sigla, $tpAmb);
            }
            if ($indNFe == '') {
                $indNFe = '0';
            }
            if ($indEmi == '') {
                $indEmi = '0';
            }
            //identifica��o do servi�o
            $servico = 'NfeConsultaDest';
            //recupera��o da vers�o
            $versao = $aURL[$servico]['version'];
            //recupera��o da url do servi�o
            $urlservico = $aURL[$servico]['URL'];
            //recupera��o do m�todo
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do servi�o
            $operation = $aURL[$servico]['operation'];
            $namespace = $this->URLPortal.'/wsdl/'.$operation;
            //monta a consulta
            $cons = '';
            $cons .= "<consNFeDest xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $cons .= "<tpAmb>$tpAmb</tpAmb><xServ>CONSULTAR NFE DEST</xServ>";
            $cons .= "<CNPJ>$this->cnpj</CNPJ><indNFe>$indNFe</indNFe>";
            $cons .= "<indEmi>$indEmi</indEmi><ultNSU>$ultNSU</ultNSU></consNFeDest>";
            //montagem do cabe�alho da comunica��o SOAP
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF>";
            $cabec .= "<versaoDados>$versao</versaoDados></nfeCabecMsg>";
            //montagem dos dados da mensagem SOAP
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$cons</nfeDadosMsg>";
            //grava solicita��o em temp
            if (!file_put_contents($this->temDir."$this->cnpj-$ultNSU-$datahora-LNFe.xml", $cons)) {
                $msg = "Falha na gravacao do arquivo LNFe (Lista de NFe)!!";
                $this->pSetError($msg);
            }
            //envia dados via SOAP
            $retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            //verifica o retorno
            if (!$retorno) {
                //n�o houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar dados de retorno
            $indCont = 0;
            $xmlLNFe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlLNFe->formatOutput = false;
            $xmlLNFe->preserveWhiteSpace = false;
            $xmlLNFe->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retConsNFeDest = $xmlLNFe->getElementsByTagName("retConsNFeDest")->item(0);
            if (isset($retConsNFeDest)) {
                $cStat = !empty($retConsNFeDest->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                    $retConsNFeDest->getElementsByTagName('cStat')->item(0)->nodeValue :
                    '';
                $xMotivo = !empty($retConsNFeDest->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                    $retConsNFeDest->getElementsByTagName('xMotivo')->item(0)->nodeValue :
                    '';
                $ultNSU  = !empty($retConsNFeDest->getElementsByTagName('ultNSU')->item(0)->nodeValue) ?
                    $retConsNFeDest->getElementsByTagName('ultNSU')->item(0)->nodeValue :
                    '';
                $indCont = !empty($retConsNFeDest->getElementsByTagName('indCont')->item(0)->nodeValue) ?
                    $retConsNFeDest->getElementsByTagName('indCont')->item(0)->nodeValue :
                    0;
            } else {
                $cStat = '';
            }
            if ($cStat == '') {
                //houve erro
                $msg = "cStat est� em branco, ";
                $msg .= "houve erro na comunica��o Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //erro no processamento
            if ($cStat != '137' && $cStat != '138') {
                //se cStat <> 137 ou 138 houve erro e o lote foi rejeitado
                $msg = "A requisi��o foi rejeitada : $cStat - $xMotivo\n";
                throw new nfephpException($msg);
            }
            //podem existir NFe emitidas para este destinat�rio
            $aNFe = array();
            $aCanc = array();
            $aCCe = array();
            $ret =  $xmlLNFe->getElementsByTagName("ret");
            foreach ($ret as $k => $d) {
                $resNFe = $ret->item($k)->getElementsByTagName('resNFe')->item(0);
                $resCanc = $ret->item($k)->getElementsByTagName('resCanc')->item(0);
                $resCCe = $ret->item($k)->getElementsByTagName('resCCe')->item(0);
                if (isset($resNFe)) {
                    //existem notas emitidas para esse cnpj
                    $nsu = $resNFe->getAttribute('NSU');
                    $chNFe = $resNFe->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $CNPJ = $resNFe->getElementsByTagName('CNPJ')->item(0)->nodeValue;
                    $xNome = $resNFe->getElementsByTagName('xNome')->item(0)->nodeValue;
                    $dEmi = $resNFe->getElementsByTagName('dEmi')->item(0)->nodeValue;
                    $vNF = $resNFe->getElementsByTagName('vNF')->item(0)->nodeValue;
                    $dhRecbto= $resNFe->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $tpNF = $resNFe->getElementsByTagName('tpNF')->item(0)->nodeValue;
                    $cSitNFe = $resNFe->getElementsByTagName('cSitNFe')->item(0)->nodeValue;
                    $cSitConf = $resNFe->getElementsByTagName('cSitConf')->item(0)->nodeValue;
                    $aNFe[] = array(
                        'chNFe'=>$chNFe,
                        'NSU'=>$nsu,
                        'CNPJ'=>$CNPJ,
                        'xNome'=>$xNome,
                        'dEmi'=>$dEmi,
                        'vNF'=>$vNF,
                        'dhRecbto'=>$dhRecbto,
                        'tpNF'=>$tpNF,
                        'cSitNFe'=>$cSitNFe,
                        'cSitconf'=>$cSitConf
                    );
                }//fim resNFe
                if (isset($resCanc)) {
                    //existem notas canceladas para esse cnpj
                    $nsu = $resCanc->getAttribute('NSU');
                    $chNFe = $resCanc->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $CNPJ = $resCanc->getElementsByTagName('CNPJ')->item(0)->nodeValue;
                    $xNome = $resCanc->getElementsByTagName('xNome')->item(0)->nodeValue;
                    $dEmi = $resCanc->getElementsByTagName('dEmi')->item(0)->nodeValue;
                    $vNF = $resCanc->getElementsByTagName('vNF')->item(0)->nodeValue;
                    $dhRecbto= $resCanc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $tpNF = $resCanc->getElementsByTagName('tpNF')->item(0)->nodeValue;
                    $cSitNFe = $resCanc->getElementsByTagName('cSitNFe')->item(0)->nodeValue;
                    $cSitConf = $resCanc->getElementsByTagName('cSitConf')->item(0)->nodeValue;
                    $aCanc[] = array(
                        'chNFe'=>$chNFe,
                        'NSU'=>$nsu,
                        'CNPJ'=>$CNPJ,
                        'xNome'=>$xNome,
                        'dEmi'=>$dEmi,
                        'vNF'=>$vNF,
                        'dhRecbto'=>$dhRecbto,
                        'tpNF'=>$tpNF,
                        'cSitNFe'=>$cSitNFe,
                        'cSitconf'=>$cSitConf
                    );
                }//fim resCanc
                if (isset($resCCe)) {
                    //existem cartas de corre��o emitidas para esse cnpj
                    $nsu = $resCCe->getAttribute('NSU');
                    $chNFe = $resCCe->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $tpEvento = $resCCe->getElementsByTagName('tpEvento')->item(0)->nodeValue;
                    $nSeqEvento = $resCCe->getElementsByTagName('nSeqEvento')->item(0)->nodeValue;
                    $dhEvento = $resCCe->getElementsByTagName('dhEvento')->item(0)->nodeValue;
                    $dhRecbto= $resCCe->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $descEvento = $resCCe->getElementsByTagName('descEvento')->item(0)->nodeValue;
                    $xCorrecao = $resCCe->getElementsByTagName('xCorrecao')->item(0)->nodeValue;
                    $tpNF = $resCCe->getElementsByTagName('tpNF')->item(0)->nodeValue;
                    $aCCe[] = array(
                        'chNFe'=>$chNFe,
                        'NSU'=>$nsu,
                        'tpEvento'=>$tpEvento,
                        'nSeqEvento'=>$nSeqEvento,
                        'dhEvento'=>$dhEvento,
                        'dhRecbto'=>$dhRecbto,
                        'descEvento'=>$descEvento,
                        'xCorrecao'=>$xCorrecao,
                        'tpNF'=>$tpNF
                    );
                }//fim resCCe
            }//fim foreach ret
            //salva o arquivo xml
            if (!file_put_contents($this->temDir."$this->cnpj-$ultNSU-$datahora-resLNFe.xml", $retorno)) {
                $msg = "Falha na gravacao do arquivo resLNFe!!";
                $this->pSetError($msg);
            }
            if ($ultNSU != '' && $indCont == 1) {
                //grava o ultimo NSU informado no arquivo
                $this->putUltNSU($sigla, $tpAmb, $ultNSU);
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }//fim catch
        $resp = array('indCont'=>$indCont,'ultNSU'=>$ultNSU,'NFe'=>$aNFe,'Canc'=>$aCanc,'CCe'=>$aCCe);
        return $retorno;
    }//fim getListNFe

    /**
     * getNFe
     * Download da NF-e para uma determinada Chave de Acesso informada,
     * para as NF-e confirmadas pelo destinat�rio. As NFe baixadas ser�o salvas
     * na pasta de recebidas
     *
     * ESSE SEVI�O N�O EST� TOTALMENTE OPERACIONAL EXISTE APENAS NO SEFAZ DO RS E SVAN
     *
     * Este servi�o n�o suporta SVC !!
     *
     * @name getNFe
     * @param boolean $AN   true usa ambiente nacional, false usa o SEFAZ do emitente da NF
     * @param string $chNFe chave da NFe
     * @param string $tpAmb tipo de ambiente
     * @return mixed FALSE ou xml de retorno
     *
     * TODO: quando o servi�o estiver funcional extrair o xml da NFe e colocar
     * no diretorio correto
     */
    public function getNFe($AN = true, $chNFe = '', $tpAmb = '')
    {
        try {
            if ($chNFe == '') {
                $msg = 'Uma chave de NFe deve ser passada como par�metro da fun��o.';
                throw new nfephpException($msg);
            }
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if ($AN) {
                $aURL = $this->pLoadSEFAZ($tpAmb, 'AN');
            } else {
                //deve se verificado se NFe emitidas em SCAN, com s�ries come�ando com 9
                //podem ser obtidas no sefaz do emitente DUVIDA!!!
                //obtem a SEFAZ do emissor
                $cUF = substr($chNFe, 0, 2);
                $siglaUF = $this->siglaUFList[$cUF];
                $aURL = $this->pLoadSEFAZ($tpAmb, $siglaUF);
            }
            //identifica��o do servi�o
            $servico = 'NfeDownloadNF';
            //recupera��o da vers�o
            $versao = $aURL[$servico]['version'];
            //recupera��o da url do servi�o
            $urlservico = $aURL[$servico]['URL'];
            //recupera��o do m�todo
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do servi�o
            $operation = $aURL[$servico]['operation'];
            $namespace = $this->URLPortal.'/wsdl/'.$operation;
            if ($urlservico == '') {
                throw new nfephpException('Nao existe este servico na SEFAZ consultada.');
            }
            //montagem do cabe�alho da comunica��o SOAP
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF><versaoDados>$versao</versaoDados></nfeCabecMsg>";
            //montagem dos dados da mensagem SOAP
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">"
                    . "<downloadNFe xmlns=\"$this->URLPortal\" versao=\"$versao\">"
                    . "<tpAmb>$tpAmb</tpAmb>"
                    . "<xServ>DOWNLOAD NFE</xServ>"
                    . "<CNPJ>$this->cnpj</CNPJ>"
                    . "<chNFe>$chNFe</chNFe>"
                    . "</downloadNFe></nfeDadosMsg>";
            //envia dados via SOAP e verifica o retorno
            if (!$retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb)) {
                throw new nfephpException("Nao houve retorno Soap verifique a mensagem de erro e o debug!!");
            }
            //salva arquivo de retorno contendo todo o XML da SEFAZ
            $fileName = $this->temDir."$chNFe-resDWNFe.xml";
            if (!file_put_contents($fileName, $retorno)) {
                $msg = "Falha na gravacao do arquivo $fileName!!";
                $this->pSetError($msg);
            }
            //tratar dados de retorno
            $xmlDNFe = new DomDocumentNFePHP($retorno);
            $retDownloadNFe = $xmlDNFe->getElementsByTagName("retDownloadNFe")->item(0);
            if (isset($retDownloadNFe)) {
                $cStat = !empty($retDownloadNFe->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                        $retDownloadNFe->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                $xMotivo = !empty($retDownloadNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                        $retDownloadNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                $dhResp = !empty($retDownloadNFe->getElementsByTagName('dhResp')->item(0)->nodeValue) ?
                        $retDownloadNFe->getElementsByTagName('dhResp')->item(0)->nodeValue : '';
                //existem 2 cStat, um com n� pai retDownloadNFe ($cStat) e outro no
                //n� filho retNFe($cStatRetorno)
                //para que o download seja efetuado corretamente o $cStat deve vir
                //com valor 139-Pedido de download Processado
                //e o $cStatRetorno com valor 140-Download disponibilizado
                $retNFe = $xmlDNFe->getElementsByTagName("retNFe")->item(0);
                if (isset($retNFe)) {
                    $cStatRetorno = !empty($retNFe->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                            $retNFe->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                    $xMotivoRetorno = !empty($retNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                            $retNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                } else {
                    $cStatRetorno = '';
                    $xMotivoRetorno = '';
                }
            } else {
                $cStat = '';
            }
            //status de retorno nao podem vir vazios
            if (empty($cStat)) {
                //houve erro
                $msg = "cStat est� em branco, houve erro na comunica��o verifique a mensagem de erro!";
                throw new nfephpException($msg);
            }
            //erro no processamento
            if ($cStat != '139') {
                //se cStat <> 139 ou 140 houve erro e o lote foi rejeitado
                $msg = "A requisi��o foi rejeitada : $cStat - $xMotivo\n";
                throw new nfephpException($msg);
            }
            if ($cStatRetorno != '140') {
                //pega o motivo do n� retNFe, com a descri�ao da rejei�ao
                $msg = "N�o houve o download da NF : $cStatRetorno - $xMotivoRetorno\n";
                throw new nfephpException($msg);
            }
            //verifica como deve extrair o XML da NF-e, pois existem 3 possibilidades:
            //JR13-procNFeZip ~ou~ JR14-procNFe ~ou~ JR17-procNFeGrupoZip onde JR13 e JR14
            //s�o elementos e JR17 � um grupo
            $retNFe_procNFeZip = $retNFe->getElementsByTagName('procNFeZip')->item(0);
            $retNFe_procNFe = $retNFe->getElementsByTagName('procNFe')->item(0);
            $retNFe_procNFeGrupoZip = $retNFe->getElementsByTagName('procNFeGrupoZip')->item(0);
            if (isset($retNFe_procNFeZip)) {
                $xml = ''; //implementar...
            } elseif (isset($retNFe_procNFe)) {
                //elemento "JR14_procNFe" contendo a estrutura "nfeProc", j� descompactada.
                $nfeProc = $xmlDNFe->getElementsByTagName("nfeProc")->item(0);
                //cria novo documento DOM para importar e adicionar o elemento
                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->formatOutput = false;
                $dom->preserveWhiteSpace = false;
                // Importa o node e todo o seu conteudo e acrescenta ao node principal
                $node = $dom->importNode($nfeProc, true);
                $dom->appendChild($node);
                $xml = $dom->saveXML();
            } elseif (isset($retNFe_procNFeGrupoZip)) {
                //grupo contendo a NF-e compactada e o Protocolo de Autoriza��o compactado (padr�o gZip).
                //extrai a NF-e do elemento JR18_NFeZip e extrai o protocolo de autoriza��o de uso do elemento
                //JR19_protNFeZip (ambos s�o obrigat�rios)
                $nfe = $this->pGunzip2(base64_decode($retNFe_procNFeGrupoZip->getElementsByTagName('NFeZip')->item(0)->nodeValue));
                $prot = $this->pGunzip2(base64_decode($retNFe_procNFeGrupoZip->getElementsByTagName('protNFeZip')->item(0)->nodeValue));
                //tem a NF-e e o protocolo de autoriza��o, agora adiciona o protocolo; para isso,
                //cria dois arquivos tempor�rios e chama o addProt()
                $nfeTempFile = file_put_contents($this->temDir.$chNFe.'-nfe.xml', $nfe);
                $protTempFile = file_put_contents($this->temDir.$chNFe.'-prot.xml', $prot);
                $xml = $this->addProt($this->temDir.$chNFe.'-nfe.xml', $this->temDir.$chNFe.'-prot.xml');
            }
            $fileName = $this->recDir."$chNFe-procNFe.xml";
            if (!file_put_contents($fileName, $xml)) {
                $msg = "Falha na gravacao do arquivo NFe $fileName!!";
                $this->pSetError($msg);
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }//fim catch
        return $retorno;
    }//fim getNFe

    /**
     * Solicita inutiliza��o de uma s�rie de n�meros de NF. O processo de inutiliza��o
     * ser� gravado na "pasta Inutilizadas".
     *
     * ATEN��O: este webservice *n�o* � oferecido pelas SVC (Sefaz Virtual de Conting�ncia)
     * conforme NT 2013.007 vers�o "1.02" de Dezembro/2013.
     *
     * @name inutNF
     * @param string  $nAno     ano com 2 digitos
     * @param string  $nSerie   serie da NF 1 at� 3 digitos
     * @param integer $nIni     numero inicial 1 at� 9 digitos zero a esq
     * @param integer $nFin     numero Final 1 at� 9 digitos zero a esq
     * @param string  $xJust    justificativa 15 at� 255 digitos
     * @param string  $tpAmb    Tipo de ambiente 1-produ��o ou 2 homologa��o
     * @param array   $aRetorno Array com os dados de Retorno
     * @return mixed false ou string com o xml do processo de inutiliza��o
     */
    public function inutNF(
        $nAno = '',
        $nSerie = '1',
        $nIni = '',
        $nFin = '',
        $xJust = '',
        $tpAmb = '',
        &$aRetorno = array()
    ) {
        //retorno da fun��o
        $aRetorno = array(
            'bStat'=>false,
            'tpAmb'=>'',
            'verAplic'=>'',
            'cStat'=>'',
            'xMotivo'=>'',
            'cUF'=>'',
            'ano'=>'',
            'CNPJ'=>'',
            'mod'=>'',
            'serie'=>'',
            'nNFIni'=>'',
            'nNFFin'=>'',
            'dhRecbto'=>'',
            'nProt'=>'',
            'idInut' => '');
        //valida dos dados de entrada
        if ($nAno == '' || $nIni == '' || $nFin == '' || $xJust == '') {
            $msg = "N�o foi passado algum dos parametos necess�rios ANO=$nAno inicio=$nIni "
                   ."fim=$nFin justificativa=$xJust.\n";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //valida justificativa
        if (strlen($xJust) < 15) {
            $msg = "A justificativa deve ter pelo menos 15 digitos!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        if (strlen($xJust) > 255) {
            $msg = "A justificativa deve ter no m�ximo 255 digitos!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        if (! is_numeric($nAno) || ! is_numeric($nSerie) || ! is_numeric($nIni) || ! is_numeric($nFin)) {
            $msg = "'Ano':$nAno, "
                . "'S�rie':$nSerie, "
                . "'n�mero inicial':$nIni e "
                . "'n�mero final':$nFin devem ser numericos!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //remove acentos e outros caracteres da justificativa
        $xJust = $this->pCleanString($xJust);
        // valida o campo ano
        if (strlen($nAno) > 2) {
            $msg = "O ano tem mais de 2 digitos. Corrija e refa�a o processo!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        } else {
            if (strlen($nAno) < 2) {
                $msg = "O ano tem menos de 2 digitos. Corrija e refa�a o processo!!";
                $this->pSetError($msg);
                if ($this->exceptions) {
                    throw new nfephpException($msg);
                }
                return false;
            }
        }
        //valida o campo serie
        if (strlen($nSerie) == 0 || strlen($nSerie) > 3) {
            $msg = "O campo serie est� errado: $nSerie. Corrija e refa�a o processo!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //valida o campo numero inicial
        if (strlen($nIni) < 1 || strlen($nIni) > 9) {
            $msg = "O campo numero inicial est� errado: $nIni. Corrija e refa�a o processo!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //valida o campo numero final
        if (strlen($nFin) < 1 || strlen($nFin) > 9) {
            $msg = "O campo numero final est� errado: $nFin. Corrija e refa�a o processo!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //valida contingencias, nao podem estar habilitadas pois este servi�o n�o se aplica para SVC
        if ($this->enableSVCAN || $this->enableSVCRS) {
            $msg = "Inutilizacao nao pode ser usada em contingencia SVC!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
        }
        //valida tipo de ambiente
        if ($tpAmb == '') {
            $tpAmb = $this->tpAmb;
        }
        if ($tpAmb == $this->tpAmb) {
            $aURL = $this->aURL;
        } else {
            $aURL = $this->pLoadSEFAZ($tpAmb, $this->siglaUF);
        }
        //identifica��o do servi�o
        $servico = 'NfeInutilizacao';
        //recupera��o da vers�o
        $versao = $aURL[$servico]['version'];
        //recupera��o da url do servi�o
        $urlservico = $aURL[$servico]['URL'];
        //recupera��o do m�todo
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do servi�o
        $operation = $aURL[$servico]['operation'];
        $namespace = $this->URLPortal.'/wsdl/'.$operation;
        //Identificador da TAG a ser assinada formada com C�digo da UF +
        //Ano (2 posi��es) + CNPJ + modelo + s�rie + nro inicial e nro final
        //precedida do literal "ID"
        // 43 posi��es
        //     2      4       6       20      22    25       34      43
        //     2      2       2       14       2     3        9       9
        $id = 'ID'
                . $this->cUF
                . $nAno
                . $this->cnpj
                . $this->modelo
                . str_pad($nSerie, 3, '0', STR_PAD_LEFT)
                . str_pad($nIni, 9, '0', STR_PAD_LEFT)
                . str_pad($nFin, 9, '0', STR_PAD_LEFT);
        $aRetorno['idInut'] = $id;
        //montagem do cabe�alho da comunica��o SOAP
        $cabec = "<nfeCabecMsg xmlns=\"$namespace\">"
                . "<cUF>$this->cUF</cUF>"
                . "<versaoDados>$versao</versaoDados>"
                . "</nfeCabecMsg>";
        //montagem do corpo da mensagem
        $dXML = "<inutNFe xmlns=\"$this->URLnfe\" versao=\"$versao\">"
                . "<infInut Id=\"$id\">"
                . "<tpAmb>$tpAmb</tpAmb>"
                . "<xServ>INUTILIZAR</xServ>"
                . "<cUF>$this->cUF</cUF>"
                . "<ano>$nAno</ano>"
                . "<CNPJ>$this->cnpj</CNPJ>"
                . "<mod>$this->modelo</mod>"
                . "<serie>$nSerie</serie>"
                . "<nNFIni>$nIni</nNFIni>"
                . "<nNFFin>$nFin</nNFFin>"
                . "<xJust>$xJust</xJust>"
                . "</infInut></inutNFe>";
        //assina a lsolicita��o de inutiliza��o
        $dXML = $this->signXML($dXML, 'infInut');
        $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dXML</nfeDadosMsg>";
        //remove as tags xml que porventura tenham sido inclusas
        $dados = $this->pClearXml($dados, true);
        //grava a solicita��o de inutiliza��o
        if (!file_put_contents($this->temDir.$id.'-pedInut.xml', $dXML)) {
            $msg = "Falha na gravacao do pedido de inutiliza��o!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
        }
        //envia a solicita��o via SOAP
        $retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        //verifica o retorno
        if (!$retorno) {
            $msg = "Nao houve retorno Soap verifique o debug!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //tratar dados de retorno
        $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $retorno = "";
        if ($doc->getElementsByTagName("nfeInutilizacaoNF2Result")->item(0) != null){
            $retorno = $doc->getElementsByTagName("infInut")->item(0);
        }else{
            $retorno = $doc->getElementsByTagName("infInut")->item(1);
        }
        $cStat = !empty($retorno->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
        $xMotivo = !empty($retorno->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
        $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
        if ($cStat == '') {
            //houve erro
            $msg = "Nao houve retorno Soap verifique o debug!!";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        //verificar o status da solicita��o
        if ($cStat != '102') {
            //houve erro
            $msg = "Rejei��o : $cStat - $xMotivo";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
            return false;
        }
        // tipo de ambiente
        $aRetorno['tpAmb'] = $retorno->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        // verss�o do aplicativo
        $aRetorno['verAplic'] = $retorno->getElementsByTagName('verAplic')->item(0)->nodeValue;
        // status do servi�o
        $aRetorno['cStat'] = $cStat;
        // motivo da resposta (opcional)
        $aRetorno['xMotivo'] = $xMotivo;
        // C�digo da UF que atendeu a solicita��o
        $aRetorno['cUF'] = $retorno->getElementsByTagName('cUF')->item(0)->nodeValue;
        // Ano de inutiliza��o da numera��o
        $aRetorno['ano'] = $retorno->getElementsByTagName('ano')->item(0)->nodeValue;
        // CNPJ do emitente
        $aRetorno['CNPJ'] = $retorno->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        // Modelo da NF-e
        $aRetorno['mod'] = $retorno->getElementsByTagName('mod')->item(0)->nodeValue;
        // S�rie da NF-e
        $aRetorno['serie'] = $retorno->getElementsByTagName('serie')->item(0)->nodeValue;
        // N�mero da NF-e inicial a ser inutilizada
        $aRetorno['nNFIni'] = $retorno->getElementsByTagName('nNFIni')->item(0)->nodeValue;
        // N�mero da NF-e final a ser inutilizada
        $aRetorno['nNFFin'] = $retorno->getElementsByTagName('nNFFin')->item(0)->nodeValue;
        // data e hora do retorno a opera��o (opcional)
        $aRetorno['dhRecbto'] = !empty($retorno->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ?
                                 date("d/m/Y H:i:s", $this->pConvertTime($retorno->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
        // N�mero do Protocolo de Inutiliza��o
        $aRetorno['nProt'] = $retorno->getElementsByTagName('nProt')->item(0)->nodeValue;
        $aRetorno['bStat'] = true;
        //gravar o retorno na pasta temp
        $nome = $this->temDir.$id.'-retInut.xml';
        $nome = $doc->save($nome);
        $retInutNFe = $doc->getElementsByTagName("retInutNFe")->item(0);
        //preparar o processo de inutiliza��o
        $inut = new DOMDocument('1.0', 'utf-8');
        $inut->formatOutput = false;
        $inut->preserveWhiteSpace = false;
        $inut->loadXML($dXML, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $inutNFe = $inut->getElementsByTagName("inutNFe")->item(0);
        //Processo completo solicita��o + protocolo
        $procInut = new DOMDocument('1.0', 'utf-8');
        $procInut->formatOutput = false;
        $procInut->preserveWhiteSpace = false;
        //cria a tag procInutNFe
        $procInutNFe = $procInut->createElement('procInutNFe');
        $procInut->appendChild($procInutNFe);
        //estabele o atributo de vers�o
        $inutProc_att1 = $procInutNFe->appendChild($procInut->createAttribute('versao'));
        $inutProc_att1->appendChild($procInut->createTextNode($versao));
        //estabelece o atributo xmlns
        $inutProc_att2 = $procInutNFe->appendChild($procInut->createAttribute('xmlns'));
        $inutProc_att2->appendChild($procInut->createTextNode($this->URLPortal));
        //carrega o node cancNFe
        $node1 = $procInut->importNode($inutNFe, true);
        $procInutNFe->appendChild($node1);
        //carrega o node retEvento
        $node2 = $procInut->importNode($retInutNFe, true);
        $procInutNFe->appendChild($node2);
        //salva o xml como string em uma vari�vel
        $procXML = $procInut->saveXML();
        //remove as informa��es indesejadas
        $procXML  = $this->pClearXml($procXML, false);
        //salva o arquivo xml
        if (! file_put_contents($this->inuDir."$id-procInut.xml", $procXML)) {
            $msg = "Falha na gravacao da procInut!!\n";
            $this->pSetError($msg);
            if ($this->exceptions) {
                throw new nfephpException($msg);
            }
        }
        return $procXML;
    } //fim inutNFe

    /**
     * cancelEvent
     * Solicita o cancelamento de NFe autorizada
     * - O xml do evento de cancelamento ser� salvo na pasta Canceladas
     *
     * @name cancelEvent
     * @param string $chNFe
     * @param string $nProt
     * @param string $xJust
     * @param number $tpAmb
     * @param array  $aRetorno
     */
    public function cancelEvent($chNFe = '', $nProt = '', $xJust = '', $tpAmb = '', &$aRetorno = array())
    {
        try {
            //retorno da fun��o
            $aRetorno = array(
                'bStat'=>false,
                'tpAmb'=>'',
                'verAplic'=>'',
                'cStat'=>'',
                'xMotivo'=>'',
                'nProt'=>'',
                'chNFe'=>'',
                'dhRecbto'=>'');
            //valida��o dos dados de entrada
            if ($chNFe == '' || $nProt == '' || $xJust == '') {
                $msg = "N�o foi passado algum dos par�metros necess�rios "
                        . "ID=$chNFe ou protocolo=$nProt ou justificativa=$xJust.";
                throw new nfephpException($msg);
            }
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if (strlen($xJust) < 15) {
                $msg = "A justificativa deve ter pelo menos 15 digitos!!";
                throw new nfephpException($msg);
            }
            if (strlen($xJust) > 255) {
                $msg = "A justificativa deve ter no m�ximo 255 digitos!!";
                throw new nfephpException($msg);
            }
            if (strlen($chNFe) != 44) {
                $msg = "Uma chave de NFe v�lida n�o foi passada como par�metro $chNFe.";
                throw new nfephpException($msg);
            }
            //estabelece o codigo do tipo de evento CANCELAMENTO
            $tpEvento = '110111';
            $descEvento = 'Cancelamento';
            //para cancelamento o numero sequencia do evento sempre ser� 1
            $nSeqEvento = '1';
            //remove qualquer caracter especial
            $xJust = $this->pCleanString($xJust);
            //verifica se alguma das conting�ncias est� habilitada
            if ($this->enableSVCAN) {
                $aURL = $this->pLoadSEFAZ($tpAmb, self::CONTINGENCIA_SVCAN);
            } elseif ($this->enableSVCRS) {
                $aURL = $this->pLoadSEFAZ($tpAmb, self::CONTINGENCIA_SVCRS);
            } else {
                $aURL = $this->aURL;
            }
            $numLote = $this->pGeraNumLote();
            //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
            $dhEvento = date('Y-m-d\TH:i:s').$this->timeZone;
            //se o envio for para svan mudar o numero no org�o para 91
            if ($this->enableSVAN) {
                $cOrgao='90';
            } else {
                $cOrgao=$this->cUF;
            }
            //montagem do namespace do servi�o
            $servico = 'RecepcaoEvento';
            //recupera��o da vers�o
            $versao = $aURL[$servico]['version'];
            //recupera��o da url do servi�o
            $urlservico = $aURL[$servico]['URL'];
            //recupera��o do m�todo
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do servi�o
            $operation = $aURL[$servico]['operation'];
            $namespace = $this->URLPortal.'/wsdl/'.$operation;
            //de acordo com o manual vers�o 5 de mar�o de 2012
            // 2   +    6     +    44         +   2  = 54 digitos
            //"ID" + tpEvento + chave da NF-e + nSeqEvento
            //garantir que existam 2 digitos em nSeqEvento para montar o ID com 54 digitos
            if (strlen(trim($nSeqEvento))==1) {
                $zenSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
            } else {
                $zenSeqEvento = trim($nSeqEvento);
            }
            $eventId = "ID".$tpEvento.$chNFe.$zenSeqEvento;
            //monta mensagem
            $Ev = '';
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$eventId\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$versao\">";
            $Ev .= "<descEvento>$descEvento</descEvento>";
            $Ev .= "<nProt>$nProt</nProt>";
            $Ev .= "<xJust>$xJust</xJust>";
            $Ev .= "</detEvento></infEvento></evento>";
            //assinatura dos dados
            $tagid = 'infEvento';
            $Ev = $this->signXML($Ev, $tagid);
            $Ev = $this->pClearXml($Ev, true);
            //carrega uma matriz tempor�ria com os eventos assinados
            //montagem dos dados
            $dados = '';
            $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dados .= "<idLote>$numLote</idLote>";
            $dados .= $Ev;
            $dados .= "</envEvento>";
            //montagem da mensagem
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF>"
                    . "<versaoDados>$versao</versaoDados></nfeCabecMsg>";
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
            //grava solicita��o em temp
            $arqName = $this->temDir."$chNFe-$nSeqEvento-eventCanc.xml";
            if (!file_put_contents($arqName, $Ev)) {
                $msg = "Falha na gravacao do arquivo $arqName";
                $this->pSetError($msg);
            }
            //envia dados via SOAP
            $retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            //verifica o retorno
            if (!$retorno) {
                //n�o houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar dados de retorno
            $xmlretEvent = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlretEvent->formatOutput = false;
            $xmlretEvent->preserveWhiteSpace = false;
            $xmlretEvent->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retEnvEvento = $xmlretEvent->getElementsByTagName("retEnvEvento")->item(0);
            $cStat = !empty($retEnvEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                    $retEnvEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEnvEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                    $retEnvEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat == '') {
                //houve erro
                $msg = "cStat est� em branco, houve erro na comunica��o Soap "
                        . "verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar erro de vers�o do XML
            if ($cStat == '238' || $cStat == '239') {
                $this->pTrata239($retorno, $this->siglaUF, $tpAmb, $servico, $versao);
                $msg = "Vers�o do arquivo XML n�o suportada no webservice!!";
                throw new nfephpException($msg);
            }
            //erro no processamento cStat <> 128
            if ($cStat != 128) {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "Retorno de ERRO: $cStat - $xMotivo";
                throw new nfephpException($msg);
            }
            //o lote foi processado cStat == 128
            $retEvento = $xmlretEvent->getElementsByTagName("retEvento")->item(0);
            $cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat != 135 && $cStat != 155) {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "Retorno de ERRO: $cStat - $xMotivo";
                throw new nfephpException($msg);
            }
            $aRetorno['bStat'] = true;
            // tipo de ambiente
            $aRetorno['tpAmb'] = $retEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            // verss�o do aplicativo
            $aRetorno['verAplic'] = $retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue;
            // status do servi�o
            $aRetorno['cStat'] = $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue;
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            // Numero de Protocolo
            $aRetorno['nProt'] = $retEvento->getElementsByTagName('nProt')->item(0)->nodeValue;
            // Chave
            $aRetorno['chNFe'] = $retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue;
            // data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue) ?
                                    date("d/m/Y H:i:s", $this->pConvertTime($retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue)) : '';
            //o evento foi aceito cStat == 135 ou cStat == 155
            //carregar o evento
            $xmlenvEvento = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlenvEvento->formatOutput = false;
            $xmlenvEvento->preserveWhiteSpace = false;
            $xmlenvEvento->loadXML($Ev, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $evento = $xmlenvEvento->getElementsByTagName("evento")->item(0);
            //Processo completo solicita��o + protocolo
            $xmlprocEvento = new DOMDocument('1.0', 'utf-8');
            $xmlprocEvento->formatOutput = false;
            $xmlprocEvento->preserveWhiteSpace = false;
            //cria a tag procEventoNFe
            $procEventoNFe = $xmlprocEvento->createElement('procEventoNFe');
            $xmlprocEvento->appendChild($procEventoNFe);
            //estabele o atributo de vers�o
            $eventProc_att1 = $procEventoNFe->appendChild($xmlprocEvento->createAttribute('versao'));
            $eventProc_att1->appendChild($xmlprocEvento->createTextNode($versao));
            //estabelece o atributo xmlns
            $eventProc_att2 = $procEventoNFe->appendChild($xmlprocEvento->createAttribute('xmlns'));
            $eventProc_att2->appendChild($xmlprocEvento->createTextNode($this->URLPortal));
            //carrega o node evento
            $node1 = $xmlprocEvento->importNode($evento, true);
            $procEventoNFe->appendChild($node1);
            //carrega o node retEvento
            $node2 = $xmlprocEvento->importNode($retEvento, true);
            $procEventoNFe->appendChild($node2);
            //salva o xml como string em uma vari�vel
            $procXML = $xmlprocEvento->saveXML();
            //remove as informa��es indesejadas
            $procXML = $this->pClearXml($procXML, false);
            //salva o arquivo xml
            $arqName = $this->canDir."$chNFe-$nSeqEvento-procCanc.xml";
            if (!file_put_contents($arqName, $procXML)) {
                $msg = "Falha na gravacao do arquivo $arqName";
                $this->pSetError($msg);
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $procXML;
    } //fim cancEvent

    /**
     * envCCe
     * Envia carta de corre��o da Nota Fiscal para a SEFAZ.
     *
     * ATEN��O! Servi�o indispon�vel para SVC-XX.
     *
     * @name envCCe
     * @param   string $chNFe Chave da NFe
     * @param   string $xCorrecao Descri��o da Corre��o entre 15 e 1000 caracteres
     * @param   string $nSeqEvento numero sequencial da corre��o d 1 at� 20
     *                             isso deve ser mantido na base de dados e
     *                             as corre��es consolidadas, isto � a cada nova corre��o
     *                             devem ser inclusas as anteriores no texto.
     *                             O Web Service n�o permite a duplicidade de numera��o
     *                             e nem controla a ordem crescente
     * @param   integer $tpAmb Tipo de ambiente
     * @param   array    $aResp Array com os dados do protocolo
     * @return mixed false ou xml com a CCe
     */
    public function envCCe($chNFe = '', $xCorrecao = '', $nSeqEvento = '1', $tpAmb = '', &$aResp = array())
    {
        $aResp = array(
            'bStat' => false,
            'versao' => null,
            'idLote' => null,
            'tpAmb' => null,
            'verAplic' => null,
            'cOrgao' => null,
            'cStat' => null,
            'xMotivo' => null,
            'retEvento' => array(
                'versao' => null,
                'xMotivo' => null,
                'infEvento' => array(
                    'id' => null,
                    'tpAmb' => null,
                    'verAplic' => null,
                    'cOrgao' => null,
                    'cStat' => null,
                    'xMotivo' => null,
                    'chNFe' => null,
                    'tpEvento' => null,
                    'xEvento' => null,
                    'nSeqEvento' => null,
                    'CNPJDest' => null,
                    'CPFDest' => null,
                    'emailDest' => null,
                    'dhRegEvento' => null,
                    'nProt' => null)));

        try {
            //testa se os dados da carta de corre��o foram passados
            if ($chNFe == '' || $xCorrecao == '') {
                $msg = "Dados para a carta de corre��o n�o podem ser vazios.";
                throw new nfephpException($msg);
            }
            if (strlen($chNFe) != 44) {
                $msg = "Uma chave de NFe v�lida n�o foi passada como par�metro $chNFe.";
                throw new nfephpException($msg);
            }
            //se o numero sequencial do evento n�o foi informado ou se for invalido
            if ($nSeqEvento == '' || strlen($nSeqEvento) > 2 || !is_numeric($nSeqEvento) || $nSeqEvento < 1) {
                $msg = "N�mero sequencial da corre��o n�o encontrado ou � maior "
                        . "que 99 ou cont�m caracteres n�o num�ricos [$nSeqEvento]";
                throw new nfephpException($msg);
            }
            if (strlen($xCorrecao) < 15 || strlen($xCorrecao) > 1000) {
                $msg = "O texto da corre��o deve ter entre 15 e 1000 caracteres!";
                throw new nfephpException($msg);
            }
            //limpa o texto de corre��o para evitar surpresas
            $xCorrecao = $this->pCleanString($xCorrecao);
            //ajusta ambiente
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            $aURL = $this->aURL;
            $numLote = $this->pGeraNumLote();
            //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
            $dhEvento = date('Y-m-d').'T'.date('H:i:s').$this->timeZone;
            //se o envio for para svan mudar o numero no org�o para 91
            if ($this->enableSVAN) {
                $cOrgao='91';
            } else {
                $cOrgao=$this->cUF;
            }
            //montagem do namespace do servi�o
            $servico = 'RecepcaoEvento';
            //recupera��o da vers�o
            $versao = $aURL[$servico]['version'];
            //recupera��o da url do servi�o
            $urlservico = $aURL[$servico]['URL'];
            //recupera��o do m�todo
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do servi�o
            $operation = $aURL[$servico]['operation'];
            $namespace = $this->URLPortal.'/wsdl/'.$operation;
            //estabelece o codigo do tipo de evento
            $tpEvento = '110110';
            //de acordo com o manual vers�o 5 de mar�o de 2012
            // 2   +    6     +    44         +   2  = 54 digitos
            //"ID" + tpEvento + chave da NF-e + nSeqEvento
            //garantir que existam 2 digitos em nSeqEvento para montar o ID com 54 digitos
            if (strlen(trim($nSeqEvento))==1) {
                $zenSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
            } else {
                $zenSeqEvento = trim($nSeqEvento);
            }
            $chaveId = "ID".$tpEvento.$chNFe.$zenSeqEvento;
            $descEvento = 'Carta de Correcao';
            $xCondUso = 'A Carta de Correcao e disciplinada pelo paragrafo 1o-A do '
                    . 'art. 7o do Convenio S/N, de 15 de dezembro de 1970 e pode ser utilizada '
                    . 'para regularizacao de erro ocorrido na emissao de documento fiscal, desde que o '
                    . 'erro nao esteja relacionado com: I - as variaveis que determinam o valor do imposto '
                    . 'tais como: base de calculo, aliquota, diferenca de preco, quantidade, valor da '
                    . 'operacao ou da prestacao; II - a correcao de dados cadastrais que implique mudanca '
                    . 'do remetente ou do destinatario; III - a data de emissao ou de saida.';
            //monta mensagem
            $Ev='';
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$chaveId\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$versao\">";
            $Ev .= "<descEvento>$descEvento</descEvento>";
            $Ev .= "<xCorrecao>$xCorrecao</xCorrecao>";
            $Ev .= "<xCondUso>$xCondUso</xCondUso>";
            $Ev .= "</detEvento></infEvento></evento>";
            //assinatura dos dados
            $tagid = 'infEvento';
            $Ev = $this->signXML($Ev, $tagid);
            $Ev = $this->pClearXml($Ev, true);
            //carrega uma matriz tempor�ria com os eventos assinados
            //montagem dos dados
            $dados = '';
            $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dados .= "<idLote>$numLote</idLote>";
            $dados .= $Ev;
            $dados .= "</envEvento>";
            //montagem da mensagem
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF>"
                    . "<versaoDados>$versao</versaoDados></nfeCabecMsg>";
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
            //grava solicita��o em temp
            if (! file_put_contents($this->temDir."$chNFe-$nSeqEvento-envCCe.xml", $Ev)) {
                $msg = "Falha na gravacao do arquivo envCCe!!";
                throw new nfephpException($msg);
            }
            //envia dados via SOAP
            $retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            //verifica o retorno
            if (!$retorno) {
                //n�o houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar dados de retorno
            $xmlretCCe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlretCCe->formatOutput = false;
            $xmlretCCe->preserveWhiteSpace = false;
            $xmlretCCe->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retEnvEvento = $xmlretCCe->getElementsByTagName('retEnvEvento')->item(0);
            $retEvento = $xmlretCCe->getElementsByTagName("retEvento")->item(0);
            $cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            $aResp['cStat'] = $cStat;
            $aResp['xMotivo'] = $xMotivo;
            if ($cStat == '') {
                //houve erro
                $msg = "cStat est� em branco, houve erro na comunica��o Soap "
                        . "verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //erro no processamento cStat <> 128
            if ($cStat != 135) {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "Retorno de ERRO: $cStat - $xMotivo";
                throw new nfephpException($msg);
            }
            //a corre��o foi aceita cStat == 135
            $aResp['bStat'] = true;
            //carregar a CCe
            $xmlenvCCe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlenvCCe->formatOutput = false;
            $xmlenvCCe->preserveWhiteSpace = false;
            $xmlenvCCe->loadXML($Ev, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $evento = $xmlenvCCe->getElementsByTagName("evento")->item(0);
            //Processo completo solicita��o + protocolo
            $xmlprocCCe = new DOMDocument('1.0', 'utf-8');
            $xmlprocCCe->formatOutput = false;
            $xmlprocCCe->preserveWhiteSpace = false;
            //cria a tag procEventoNFe
            $procEventoNFe = $xmlprocCCe->createElement('procEventoNFe');
            $xmlprocCCe->appendChild($procEventoNFe);
            //estabele o atributo de vers�o
            $eventProc_att1 = $procEventoNFe->appendChild($xmlprocCCe->createAttribute('versao'));
            $eventProc_att1->appendChild($xmlprocCCe->createTextNode($versao));
            //estabelece o atributo xmlns
            $eventProc_att2 = $procEventoNFe->appendChild($xmlprocCCe->createAttribute('xmlns'));
            $eventProc_att2->appendChild($xmlprocCCe->createTextNode($this->URLPortal));
            //carrega o node evento
            $node1 = $xmlprocCCe->importNode($evento, true);
            $procEventoNFe->appendChild($node1);
            //carrega o node retEvento
            $node2 = $xmlprocCCe->importNode($retEvento, true);
            $procEventoNFe->appendChild($node2);
            //salva o xml como string em uma vari�vel
            $procXML = $xmlprocCCe->saveXML();
            //remove as informa��es indesejadas
            $procXML = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"", '', $procXML);
            $procXML = str_replace('default:', '', $procXML);
            $procXML = str_replace(':default', '', $procXML);
            $procXML = str_replace("\n", '', $procXML);
            $procXML = str_replace("\r", '', $procXML);
            $procXML = str_replace("\s", '', $procXML);
            //estrutura "retEnvEvento"
            $aRespVersao = $retEnvEvento->getAttribute('versao');
            $aResp['versao'] = !empty($aRespVersao) ? $retEnvEvento->getAttribute('versao') : '';
            $aResp['idLote'] = !empty($retEnvEvento->getElementsByTagName('idLote')->item(0)->nodeValue) ?
                    $retEnvEvento->getElementsByTagName('idLote')->item(0)->nodeValue : '';
            $aResp['tpAmb'] = !empty($retEnvEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue) ?
                    $retEnvEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue : '';
            $aResp['verAplic'] = !empty($retEnvEvento->getElementsByTagName('verAplic')->item(0)->nodeValue) ?
                    $retEnvEvento->getElementsByTagName('verAplic')->item(0)->nodeValue : '';
            $aResp['cOrgao'] = !empty($retEnvEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue) ?
                    $retEnvEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue : '';
            $aResp['cStat'] = !empty($retEnvEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                    $retEnvEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $aResp['xMotivo'] = !empty($retEnvEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                    $retEnvEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            //estrutura "retEvento"/"infEvento"
            $aRetEvento = array();
            $aInfEvento = array();
            $aRetEventoVersao = $retEvento->getAttribute('versao');
            $aInfEventoId = $retEvento->getElementsByTagName('infEvento')->item(0)->getAttribute('id');
            $aRetEvento['versao'] = !empty($aRetEventoVersao) ? $aRetEventoVersao : '';
            $aInfEvento['id'] = !empty($aInfEventoId) ? $aInfEventoId : '';
            $aInfEvento['tpAmb'] = !empty($retEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue : '';
            $aInfEvento['verAplic'] = !empty($retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue : '';
            $aInfEvento['cOrgao'] = !empty($retEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue : '';
            $aInfEvento['cStat'] = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $aInfEvento['xMotivo'] = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            $aInfEvento['chNFe'] = !empty($retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue : '';
            $aInfEvento['tpEvento'] = !empty($retEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue : '';
            $aInfEvento['nSeqEvento'] = !empty($retEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue : '';
            $aInfEvento['CNPJDest'] = !empty($retEvento->getElementsByTagName('CNPJDest')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('CNPJDest')->item(0)->nodeValue : '';
            $aInfEvento['CPFDest'] = !empty($retEvento->getElementsByTagName('CPFDest')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('CPFDest')->item(0)->nodeValue : '';
            $aInfEvento['emailDest'] = !empty($retEvento->getElementsByTagName('emailDest')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('emailDest')->item(0)->nodeValue : '';
            $aInfEvento['dhRegEvento'] = !empty($retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue : '';
            $aInfEvento['nProt'] = !empty($retEvento->getElementsByTagName('nProt')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('nProt')->item(0)->nodeValue : '';
            //adiciona os arrays na estrutura de retorno ficando
            //retorno = array('retEvento'=>array('infEvento'=>array()))
            $aRetEvento['infEvento'] = $aInfEvento;
            $aResp['retEvento'] = $aRetEvento;
            //salva o arquivo xml
            if (!file_put_contents($this->cccDir."$chNFe-$nSeqEvento-procCCe.xml", $procXML)) {
                $msg = "Falha na gravacao da procCCe!!";
                $this->pSetError($msg);
                throw new nfephpException($msg);
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $procXML;
    }//fim envCCe

    /**
     * manifDest
     * Manifesta��o do detinat�rio NT2012-002.
     *     210200 - Confirma��o da Opera��o
     *     210210 - Ci�ncia da Opera��o
     *     210220 - Desconhecimento da Opera��o
     *     210240 - Opera��o n�o Realizada
     *
     * @name manifDest
     * @param   string $chNFe Chave da NFe
     * @param   string $tpEvento Tipo do evento pode conter 2 ou 6 digitos ex. 00 ou 210200
     * @param   string $xJust Justificativa quando tpEvento = 40 ou 210240
     * @param   integer $tpAmb Tipo de ambiente
     * @param   mixed  $resp vari�vel passada como referencia e ir� conter o retorno da fun��o em um array
     * @return false ou xml do retorno do webservice
     */
    public function manifDest($chNFe = '', $tpEvento = '', $xJust = '', $tpAmb = '', &$resp = array())
    {
        try {
            if ($chNFe == '') {
                $msg = "A chave da NFe recebida � obrigat�ria.";
                throw new nfephpException($msg);
            }
            if ($tpEvento == '') {
                $msg = "O tipo de evento n�o pode ser vazio.";
                throw new nfephpException($msg);
            }
            if (strlen($tpEvento) == 2) {
                $tpEvento = "2102$tpEvento";
            }
            if (strlen($tpEvento) != 6) {
                $msg = "O comprimento do c�digo do tipo de evento est� errado.";
                throw new nfephpException($msg);
            }
            switch ($tpEvento) {
                case '210200':
                    $descEvento = 'Confirmacao da Operacao';
                    //confirma a opera��o e o recebimento da mercadoria (para as opera��es
                    //com circula��o de mercadoria)
                    //Ap�s a Confirma��o da Opera��o pelo destinat�rio, a empresa emitente
                    //fica automaticamente impedida de cancelar a NF-e
                    break;
                case '210210':
                    $descEvento = 'Ciencia da Operacao'; //encrenca !!! N�o usar
                    //O evento de "Ci�ncia da Opera��o" � um evento opcional e pode ser evitado
                    //Ap�s um per�odo determinado, todas as opera��es com "Ci�ncia da Opera��o" dever�o
                    //obrigatoriamente ter a manifesta��o final do destinat�rio declarada em um dos eventos de
                    //Confirma��o da Opera��o, Desconhecimento ou Opera��o n�o Realizada
                    break;
                case '210220':
                    $descEvento = 'Desconhecimento da Operacao';
                    //Uma empresa pode ficar sabendo das opera��es destinadas a um determinado CNPJ
                    //consultando o "Servi�o de Consulta da Rela��o de Documentos Destinados" ao seu CNPJ.
                    //O evento de "Desconhecimento da Opera��o" permite ao destinat�rio informar o seu
                    //desconhecimento de uma determinada opera��o que conste nesta rela��o, por exemplo
                    break;
                case '210240':
                    $descEvento = 'Operacao nao Realizada';
                    //n�o aceita��o no recebimento que antes se fazia com apenas um carimbo na NF
                    //opera��o n�o foi realizada (com Recusa de Recebimento da mercadoria e outros motivos),
                    //n�o cabendo neste caso a emiss�o de uma Nota Fiscal de devolu��o.
                    break;
                default:
                    $msg = "O c�digo do tipo de evento informado n�o corresponde a "
                        . "nenhum evento de manifesta��o de destinat�rio.";
                    throw new nfephpException($msg);
            }
            $resp = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','arquivo'=>'');
            if ($tpEvento == '210240' && $xJust == '') {
                    $msg = "Uma Justificativa � obrigat�ria para o evento de Opera��o n�o Realizada.";
                    throw new nfephpException($msg);
            }
            //limpa o texto de corre��o para evitar surpresas
            $xJust = $this->pCleanString($xJust);
            //ajusta ambiente
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            //utilizar AN para enviar o manifesto
            $sigla = 'AN';
            $aURL = $this->pLoadSEFAZ($tpAmb, $sigla);
            $cOrgao='91';
            $numLote = $this->pGeraNumLote();
            //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
            $dhEvento = date('Y-m-d').'T'.date('H:i:s').$this->timeZone;
            //montagem do namespace do servi�o
            $servico = 'RecepcaoEvento';
            //recupera��o da vers�o
            $versao = $aURL[$servico]['version'];
            //recupera��o da url do servi�o
            $urlservico = $aURL[$servico]['URL'];
            //recupera��o do m�todo
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do servi�o
            $operation = $aURL[$servico]['operation'];
            $namespace = $this->URLPortal.'/wsdl/'.$operation;
            // 2   +    6     +    44         +   2  = 54 digitos
            //"ID" + tpEvento + chave da NF-e + nSeqEvento
            $nSeqEvento = '1';
            $id = "ID".$tpEvento.$chNFe.'0'.$nSeqEvento;
            //monta mensagem
            $Ev='';
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$id\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$versao\">";
            $Ev .= "<descEvento>$descEvento</descEvento>";
            if ($xJust != '') {
                $Ev .= "<xJust>$xJust</xJust>";
            }
            $Ev .= "</detEvento></infEvento></evento>";
            //assinatura dos dados
            $tagid = 'infEvento';
            $Ev = $this->signXML($Ev, $tagid);
            $Ev = $this->pClearXml($Ev, true);
            //montagem dos dados
            $dados = '';
            $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dados .= "<idLote>$numLote</idLote>";
            $dados .= $Ev;
            $dados .= "</envEvento>";
            //montagem da mensagem
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF>"
                    . "<versaoDados>$versao</versaoDados></nfeCabecMsg>";
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
            //grava solicita��o em temp
            if (! file_put_contents($this->temDir."$chNFe-$nSeqEvento-envMDe.xml", $Ev)) {
                throw new nfephpException("Falha na gravacao do arquivo envMDe!!");
            }
            //envia dados via SOAP e verifica o retorno, se nao houve gera excecao
            if (!$retorno = $this->pSendSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb)) {
                throw new nfephpException("Nao houve retorno Soap verifique a mensagem de erro e o debug!!");
            }
            //tratar dados de retorno
            $xmlMDe = new DomDocumentNFePHP($retorno);
            $retEvento = $xmlMDe->getElementsByTagName("retEvento")->item(0);
            $infEvento = $xmlMDe->getElementsByTagName("infEvento")->item(0);
            $cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                    $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat == '') {
                //houve erro
                $msg = "cStat est� em branco, houve erro na comunica��o Soap "
                        . "verifique a mensagem de erro e o debug!!";
                throw new nfephpException($msg);
            }
            //tratar erro de vers�o do XML
            if ($cStat == '238' || $cStat == '239') {
                $this->pTrata239($retorno, $sigla, $tpAmb, $servico, $versao);
                $msg = "Vers�o do arquivo XML n�o suportada no webservice!!";
                throw new nfephpException($msg);
            }
            //erro no processamento
            if ($cStat != '135' && $cStat != '136') {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "O Lote foi rejeitado : $cStat - $xMotivo\n";
                throw new nfephpException($msg);
            }
            if ($cStat == '136') {
                $msg = "O Evento foi registrado mas a NFe n�o foi localizada : $cStat - $xMotivo\n";
                throw new nfephpException($msg);
            }
            //o evento foi aceito
            $xmlenvMDe = new DomDocumentNFePHP($Ev);
            $evento = $xmlenvMDe->getElementsByTagName("evento")->item(0);
            //Processo completo solicita��o + protocolo
            $xmlprocMDe = new DomDocumentNFePHP();
            //cria a tag procEventoNFe
            $procEventoNFe = $xmlprocMDe->createElement('procEventoNFe');
            $xmlprocMDe->appendChild($procEventoNFe);
            //estabele o atributo de vers�o
            $eventProc_att1 = $procEventoNFe->appendChild($xmlprocMDe->createAttribute('versao'));
            $eventProc_att1->appendChild($xmlprocMDe->createTextNode($versao));
            //estabelece o atributo xmlns
            $eventProc_att2 = $procEventoNFe->appendChild($xmlprocMDe->createAttribute('xmlns'));
            $eventProc_att2->appendChild($xmlprocMDe->createTextNode($this->URLPortal));
            //carrega o node evento
            $node1 = $xmlprocMDe->importNode($evento, true);
            $procEventoNFe->appendChild($node1);
            //carrega o node retEvento
            $node2 = $xmlprocMDe->importNode($retEvento, true);
            $procEventoNFe->appendChild($node2);
            //salva o xml como string em uma vari�vel
            $procXML = $xmlprocMDe->saveXML();
            //remove as informa��es indesejadas
            $procXML = $this->pClearXml($procXML, false);
            $filename = $this->evtDir."$chNFe-$tpEvento-$nSeqEvento-procMDe.xml";
            $resp = array('bStat'=>true,'cStat'=>$cStat,'xMotivo'=>$xMotivo,'arquivo'=>$filename);
            //salva o arquivo xml
            if (!file_put_contents($filename, $procXML)) {
                $msg = "Falha na gravacao do arquivo procMDe!!";
                throw new nfephpException($msg);
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            $resp = array('bStat'=>false,'cStat'=>$cStat,'xMotivo'=>$xMotivo,'arquivo'=>'');
            return false;
        }
        return $retorno;
    } //fim manifDest

    /**
     * pVerifySignatureXML
     * Verifica corre��o da assinatura no xml
     *
     * @param string $conteudoXML xml a ser verificado
     * @param string $tag tag que � assinada
     * @param string $err variavel passada como referencia onde s�o retornados os erros
     * @return boolean false se n�o confere e true se confere
     */
    protected function pVerifySignatureXML($conteudoXML, $tag, &$err)
    {
        // Habilita a manipula�ao de erros da libxml
        libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($conteudoXML, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $errors = libxml_get_errors();
        if (!empty($errors)) {
            $msg = "O arquivo informado n�o � um xml.";
            $err = $msg;
            return false;
        }
        $tagBase = $dom->getElementsByTagName($tag)->item(0);
        // validar digest value
        $tagInf = $tagBase->C14N(false, false, null, null);
        $hashValue = hash('sha1', $tagInf, true);
        $digestCalculado = base64_encode($hashValue);
        $digestInformado = $dom->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        if ($digestCalculado != $digestInformado) {
            $msg = "O conte�do do XML n�o confere com o Digest Value."
                    . "\nDigest calculado [{$digestCalculado}], informado no "
                    . "XML [{$digestInformado}].\nO arquivo pode estar corrompido "
                    . "ou ter sido adulterado.";
            $err = $msg;
            return false;
        }
        // Remontando o certificado
        $X509Certificate = $dom->getElementsByTagName('X509Certificate')->item(0)->nodeValue;
        $X509Certificate =  "-----BEGIN CERTIFICATE-----\n".
        $this->pSplitLines($X509Certificate)."\n-----END CERTIFICATE-----\n";
        $pubKey = openssl_pkey_get_public($X509Certificate);
        if ($pubKey === false) {
            $msg = "Ocorreram problemas ao remontar a chave p�blica. Certificado incorreto ou corrompido!!";
            $err = $msg;
            return false;
        }
        // remontando conteudo que foi assinado
        $conteudoAssinado = $dom->getElementsByTagName('SignedInfo')->item(0)->C14N(false, false, null, null);
        // validando assinatura do conteudo
        $cAssNoXML = $dom->getElementsByTagName('SignatureValue')->item(0)->nodeValue;
        $conteudoAssinadoNoXML = base64_decode(str_replace(array("\r", "\n"), '', $cAssNoXML));
        $respok = openssl_verify($conteudoAssinado, $conteudoAssinadoNoXML, $pubKey);
        if ($respok != 1) {
            $msg = "Problema ({$respok}) ao verificar a assinatura do digital!!";
            $err = $msg;
            return false;
        }
        return true;
    } // fim verifySignatureXML

    /**
     * verifyNFe
     * Verifica a validade da NFe recebida de terceiros
     *
     * @param string $file Path completo para o arquivo xml a ser verificado
     * @return boolean false se n�o confere e true se confere
     */
    public function verifyNFe($file)
    {
        try {
            //verifica se o arquivo existe
            if (!file_exists($file)) {
                $msg = "Arquivo n�o localizado!!";
                throw new nfephpException($msg);
            }
            //carrega a NFe
            $xml = file_get_contents($file);
            //testa a assinatura
            if (!$this->pVerifySignatureXML($xml, 'infNFe', $err)) {
                $msg = "Assinatura n�o confere!! ".$err;
                throw new nfephpException($msg);
            }
            //como a ssinatura confere, consultar o SEFAZ para verificar se a NF n�o foi cancelada ou � FALSA
            //carrega o documento no DOM
            $xmldoc = new DOMDocument('1.0', 'utf-8');
            $xmldoc->preservWhiteSpace = false; //elimina espa�os em branco
            $xmldoc->formatOutput = false;
            $xmldoc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $infNFe = $xmldoc->getElementsByTagName('infNFe')->item(0);
            //extrair a tag com os dados a serem assinados
            $chNFe = trim($infNFe->getAttribute("Id"));
            $chave = preg_replace('/[^0-9]/', '', $chNFe);
            $digest = $xmldoc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
            //ambiente da NFe sendo consultada
            $tpAmb = $infNFe->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            //verifica se existe o protocolo
            $protNFe = $xmldoc->getElementsByTagName('protNFe')->item(0);
            if (isset($protNFe)) {
                $nProt = $xmldoc->getElementsByTagName('nProt')->item(0)->nodeValue;
            } else {
                $nProt = '';
            }
            //busca o status da NFe na SEFAZ do estado do emitente
            $resp = array();
            $this->getProtocol('', $chave, $tpAmb, $resp);
            if ($resp['cStat']!='100') {
                $msg = "NF n�o aprovada no SEFAZ!! cStat =".$resp['cStat'] .' - '.$resp['xMotivo'] ."";
                throw new nfephpException($msg);
            }
            if (!is_array($resp['aProt'])) {
                $msg = "Falha no retorno dos dados, retornado sem o protocolo !!";
                throw new nfephpException($msg);
            }
            $nProtSefaz = $resp['aProt']['nProt'];
            $digestSefaz = $resp['aProt']['digVal'];
            //verificar numero do protocolo
            if ($nProt == '') {
                $msg = "A NFe enviada n�o cont�m o protocolo de aceita��o !!";
                throw new nfephpException($msg);
            }
            if ($nProtSefaz != $nProt) {
                $msg = "Os numeros dos protocolos n�o combinam!! nProtNF = "
                      .$nProt." <> nProtSefaz = ".$nProtSefaz."";
                throw new nfephpException($msg);
            }
            //verifica o digest
            if ($digestSefaz != $digest) {
                $msg = "Os numeros digest n�o combinam!! digValSEFAZ = "
                      .$digestSefaz." <> DigestValue = ".$digest."";
                throw new nfephpException($msg);
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    } // fim verifyNFe


    /**
     * loadSEFAZ
     * Extrai o URL, nome do servi�o e vers�o dos webservices das SEFAZ de
     * todos os Estados da Federa��o, a partir do arquivo XML de configura��es,
     * onde este � estruturado para os modelos 55 (NF-e) e 65 (NFC-e) j� que
     * os endere�os dos webservices podem ser diferentes.
     *
     * @name loadSEFAZ
     * @param  string $tpAmb     Pode ser "2-homologacao" ou "1-producao"
     * @param  string $sUF       Sigla da Unidade da Federa��o (ex. SP, RS, etc..)
     * @return mixed             false se houve erro ou array com os dados dos URLs da SEFAZ
     * @see /config/nfe_ws3_modXX.xml
     */
    protected function pLoadSEFAZ($tpAmb = '', $sUF = '')
    {
        try {
            $spathXML = $this->raizDir.'config'.DIRECTORY_SEPARATOR.$this->xmlURLfile;
            //verifica se o arquivo xml pode ser encontrado no caminho indicado
            if (!file_exists($spathXML)) {
                throw new nfephpException("O arquivo XML \"$spathXML\" nao foi encontrado");
            }
            //carrega o xml
            if (!$xmlWS = simplexml_load_file($spathXML)) {
                throw new nfephpException("O arquivo XML \"$spathXML\" parece ser invalido");
            }
            //vari�vel de retorno do m�todo
            $aUrl = array();
            //testa parametro tpAmb
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            } elseif ($tpAmb == self::AMBIENTE_PRODUCAO) {
                $sAmbiente = 'producao';
            } else {
                //for�a homologa��o em qualquer outra situa��o
                $tpAmb = self::AMBIENTE_HOMOLOGACAO;
                $sAmbiente = 'homologacao';
            }
            //valida e extrai a vari�vel cUF da lista
            if (!isset($this->aliaslist[$sUF])) {
                throw new nfephpException("UF \"$sUF\" nao encontrada na lista de alias");
            }
            $alias = $this->aliaslist[$sUF];
            //verifica se deve habilitar SVAN ou SVRS (ambos por padr�o iniciam desabilitados)
            if ($alias == self::SVAN) {
                $this->enableSVAN = true;
            } elseif ($alias == self::SVRS) {
                $this->enableSVRS = true;
            }
            //estabelece a express�o xpath de busca
            $xpathExpression = "/WS/UF[sigla='$alias']/$sAmbiente";
            //para cada "n�" no xml que atenda aos crit�rios estabelecidos
            foreach ($xmlWS->xpath($xpathExpression) as $gUF) {
                //para cada "n� filho" retonado
                foreach ($gUF->children() as $child) {
                    $u = (string) $child[0];
                    $aUrl[$child->getName()]['URL'] = $u;
                    // em cada um desses n�s pode haver atributos como a identifica��o
                    // do nome do webservice e a sua vers�o
                    foreach ($child->attributes() as $a => $b) {
                        $aUrl[$child->getName()][$a] = (string) $b;
                    }
                }
            }
            //verifica se existem outros servi�os exclusivos para esse estado
            if ($alias == self::SVAN || $alias == self::SVRS) {
                //para cada "n�" no xml que atenda aos crit�rios estabelecidos
                foreach ($xmlWS->xpath($xpathExpression) as $gUF) {
                    //para cada "n� filho" retonado
                    foreach ($gUF->children() as $child) {
                        $u = (string) $child[0];
                        $aUrl[$child->getName()]['URL'] = $u;
                        // em cada um desses n�s pode haver atributos como a identifica��o
                        // do nome do webservice e a sua vers�o
                        foreach ($child->attributes() as $a => $b) {
                            $aUrl[$child->getName()][$a] = (string) $b;
                        }
                    }
                }
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $aUrl;
    } //fim loadSEFAZ

    /**
     * loadCerts
     * Carrega o certificado pfx e gera as chaves privada e publica no
     * formato pem para a assinatura e para uso do SOAP e registra as
     * variaveis de ambiente.
     * Esta fun��o deve ser invocada antes das outras do sistema que
     * dependam do certificado.
     * Al�m disso esta fun��o tamb�m avalia a validade do certificado.
     * Os certificados padr�o A1 (que s�o usados pelo sistema) tem validade
     * limitada � 1 ano e caso esteja vencido a fun��o retornar� false.
     *
     * Resultado
     *  A fun��o ir� criar o certificado digital (chaves publicas e privadas)
     *  no formato pem e grava-los no diretorio indicado em $this->certsDir
     *  com os nomes :
     *     CNPJ_priKEY.pem
     *     CNPJ_pubKEY.pem
     *     CNPJ_certKEY.pem
     *  Estes arquivos tanb�m ser�o carregados nas vari�veis da classe
     *  $this->priKEY (com o caminho completo para o arquivo CNPJ_priKEY.pem)
     *  $this->pubKEY (com o caminho completo para o arquivo CNPJ_pubKEY.pem)
     *  $this->certKEY (com o caminho completo para o arquivo CNPJ_certKEY.pem)
     * Dependencias
     *   $this->pathCerts
     *   $this->nameCert
     *   $this->passKey
     *
     * @name loadCerts
     * @param  boolean $testaVal True testa a validade do certificado ou false n�o testa
     * @return boolean true se o certificado foi carregado e false se n�o
     */
    public function pLoadCerts($certs, $testaVal = true)
    {
        try {
            if (!function_exists('openssl_pkcs12_read')) {
                $msg = "Fun��o n�o existente: openssl_pkcs12_read!!";
                throw new nfephpException($msg);
            }

            //grava a chave privada
            $this->priKEY = $certs['priKey'];
            //grava a chave publica
            $this->pubKEY =  $certs['pubKey'];
            //monta o path completo com o nome do certificado (chave publica e privada) em formato pem
            $this->certKEY = $certs['certKey'];
            //verificar se o nome do certificado e
            if ($testaVal) {
                //verifica sua validade
                if (!$aResp = $this->pValidCerts($this->pubKEY)) {
                     $msg = "Certificado invalido!! - ".$aResp['error'];
                    throw new nfephpException($msg);
                }
            }
            //aqui verifica se existem as chaves em formato PEM
            //se existirem pega a data da validade dos arquivos PEM
            //e compara com a data de validade do PFX
            //caso a data de validade do PFX for maior que a data do PEM
            //deleta dos arquivos PEM, recria e prossegue
            $flagNovo = false;
            if ($this->pubKEY != "") {
                $cert = $this->pubKEY;
                if (!$data = openssl_x509_read($cert)) {
                    //arquivo n�o pode ser lido como um certificado
                    //ent�o deletar
                    $flagNovo = true;
                } else {
                    //pegar a data de validade do mesmo
                    $cert_data = openssl_x509_parse($data);
                    // reformata a data de validade;
                    $ano = substr($cert_data['validTo'], 0, 2);
                    $mes = substr($cert_data['validTo'], 2, 2);
                    $dia = substr($cert_data['validTo'], 4, 2);
                    //obtem o timeestamp da data de validade do certificado
                    $dValPubKey = gmmktime(0, 0, 0, $mes, $dia, $ano);
                    //compara esse timestamp com o do pfx que foi carregado
                    if ($dValPubKey < $this->pfxTimestamp) {
                        //o arquivo PEM � de um certificado anterior
                        //ent�o apagar os arquivos PEM
                        $flagNovo = true;
                    }//fim teste timestamp
                }//fim read pubkey
            } else {
                //arquivo n�o localizado
                $flagNovo = true;
            }//fim if file pubkey
            //verificar a chave privada em PEM
            if ($this->priKEY == "") {
                //arquivo n�o encontrado
                $flagNovo = true;
            }
            //verificar o certificado em PEM
            if ($this->certKEY == "") {
                //arquivo n�o encontrado
                $flagNovo = true;
            }
            //criar novos arquivos PEM
            if ($flagNovo) {
                throw new nfephpException("O certificado n?o foi informado");
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    } //fim loadCerts

    /**
     * pValidCerts
     * Valida�ao do cerificado digital, al�m de indicar
     * a validade, este metodo carrega a propriedade
     * mesesToexpire da classe que indica o numero de
     * meses que faltam para expirar a validade do mesmo
     * esta informacao pode ser utilizada para a gestao dos
     * certificados de forma a garantir que sempre estejam validos
     *
     * @name pValidCerts
     * @param    string  $cert Certificado digital no formato pem
     * @param    array   $aRetorno variavel passa por refer�ncia Array com os dados do certificado
     * @return  boolean true ou false
     */
    protected function pValidCerts($cert = '', &$aRetorno = '')
    {
        try {
            if ($cert == '') {
                $msg = "O certificado � um par�metro obrigatorio.";
                throw new nfephpException($msg);
            }
            if (!$data = openssl_x509_read($cert)) {
                $msg = "O certificado n�o pode ser lido pelo SSL - $cert .";
                throw new nfephpException($msg);
            }
            $flagOK = true;
            $errorMsg = "";
            $cert_data = openssl_x509_parse($data);
            // reformata a data de validade;
            $ano = substr($cert_data['validTo'], 0, 2);
            $mes = substr($cert_data['validTo'], 2, 2);
            $dia = substr($cert_data['validTo'], 4, 2);
            //obtem o timestamp da data de validade do certificado
            $dValid = gmmktime(0, 0, 0, $mes, $dia, $ano);
            // obtem o timestamp da data de hoje
            $dHoje = gmmktime(0, 0, 0, date("m"), date("d"), date("Y"));
            // compara a data de validade com a data atual
            if ($dValid < $dHoje) {
                $flagOK = false;
                $errorMsg = "A Validade do certificado expirou em [" .$dia.'/'.$mes.'/'.$ano."]";
            } else {
                $flagOK = $flagOK && true;
            }
            //diferen�a em segundos entre os timestamp
            $diferenca = $dValid - $dHoje;
            // convertendo para dias
            $diferenca = round($diferenca /(60*60*24), 0);
            //carregando a propriedade
            $daysToExpire = $diferenca;
            // convertendo para meses e carregando a propriedade
            $numM = ($ano * 12 + $mes);
            $numN = (date("y") * 12 + date("m"));
            //numero de meses at� o certificado expirar
            $monthsToExpire = ($numM-$numN);
            $this->certMonthsToExpire = $monthsToExpire;
            $this->certDaysToExpire = $daysToExpire;
            $this->pfxTimestamp = $dValid;
            $aRetorno = array('status'=>$flagOK,'error'=>$errorMsg,'meses'=>$monthsToExpire,'dias'=>$daysToExpire);
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    } //fim validCerts

    /**
     * cleanCerts
     * Retira as chaves de inicio e fim do certificado digital
     * para inclus�o do mesmo na tag assinatura do xml
     *
     * @name cleanCerts
     * @param    $certFile
     * @return   mixed false ou string contendo a chave digital limpa
     */
    protected function pCleanCerts($certFile)
    {
        try {
            //inicializa variavel
            $data = '';
            //carrega o certificado em um array usando o LF como referencia
            $arCert = explode("\n", $certFile);
            foreach ($arCert as $curData) {
                //remove a tag de inicio e fim do certificado
                if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0
                    && strncmp($curData, '-----END CERTIFICATE', 20) != 0) {
                    //carrega o resultado numa string
                    $data .= trim($curData);
                }
            }

        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $data;
    }//fim cleanCerts

    /**
     * listDir
     * Obtem todo o conte�do de um diretorio, e que atendam ao crit�rio indicado.
     * @param string $dir Diretorio a ser pesquisado
     * @param string $fileMatch Crit�rio de sele��o pode ser usados coringas como *-nfe.xml
     * @param boolean $retpath se true retorna o path completo dos arquivos se false so retorna o nome dos arquivos
     * @return mixed Matriz com os nome dos arquivos que atendem ao crit�rio estabelecido ou false
     */
    public function listDir($dir, $fileMatch, $retpath = false)
    {
        if (trim($fileMatch) != '' && trim($dir) != '') {
            //passar o padr�o para min�sculas
            $fileMatch = strtolower($fileMatch);
            //cria um array limpo
            $aName=array();
            //guarda o diretorio atual
            $oldDir = getcwd().DIRECTORY_SEPARATOR;
            //verifica se o parametro $dir define um diretorio real
            if (is_dir($dir)) {
                //mude para o novo diretorio
                chdir($dir);
                //pegue o diretorio
                $diretorio = getcwd().DIRECTORY_SEPARATOR;
                if (strtolower($dir) != strtolower($diretorio)) {
                    $msg = "Falha! sem permiss�o de leitura no diretorio escolhido.";
                    $this->pSetError($msg);
                    if ($this->exceptions) {
                        throw new nfephpException($msg);
                    }
                    return false;
                }
                //abra o diret�rio
                $ponteiro  = opendir($diretorio);
                $numX = 0;
                // monta os vetores com os itens encontrados na pasta
                while (false !== ($file = readdir($ponteiro))) {
                    //procure se n�o for diretorio
                    if ($file != "." && $file != "..") {
                        if (!is_dir($file)) {
                            $tfile = strtolower($file);
                            //� um arquivo ent�o
                            //verifique se combina com o $fileMatch
                            if (fnmatch($fileMatch, $tfile)) {
                                if ($retpath) {
                                    $aName[$numX] = $dir.$file;
                                } else {
                                    $aName[$numX] = $file;
                                }
                                $numX++;
                            }
                        } //endif � diretorio
                    } //endif � .ou ..
                }//endwhile
                closedir($ponteiro);
                //volte para o diretorio anterior
                chdir($oldDir);
            }//endif do teste se � um diretorio
        }//endif
        sort($aName);
        return $aName;
    } //fim listDir

    /**
     * pSendSOAP
     * Fun��o alternativa para estabelecer comunica�ao com servidor SOAP 1.2 da SEFAZ,
     * usando as chaves publica e privada parametrizadas na contru��o da classe.
     * Conforme Manual de Integra��o Vers�o 4.0.1 Utilizando cURL e n�o o SOAP nativo
     *
     * @name pSendSOAP
     * @param string $urlsefaz
     * @param string $namespace
     * @param string $cabecalho
     * @param string $dados
     * @param string $metodo
     * @param numeric $ambiente
     * @param string $siglaUF sem uso mantido apenas para compatibilidade com sendSOAP
     * @return mixed false se houve falha ou o retorno em xml do SEFAZ
     */
    protected function pSendSOAP($urlsefaz, $namespace, $cabecalho, $dados, $metodo, $ambiente = '', $siglaUF = '')
    {
        try {
            if ($urlsefaz == '') {
                $msg = "URL do webservice n�o dispon�vel no arquivo xml das URLs da SEFAZ.";
                throw new nfephpException($msg);
            }
            if ($ambiente == '') {
                $ambiente = $this->tpAmb;
            }
            $data = '';
            $data .= '<?xml version="1.0" encoding="utf-8"?>';
            $data .= '<soap12:Envelope ';
            $data .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
            $data .= 'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ';
            $data .= 'xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">';
            $data .= '<soap12:Header>';
            $data .= $cabecalho;
            $data .= '</soap12:Header>';
            $data .= '<soap12:Body>';
            $data .= $dados;
            $data .= '</soap12:Body>';
            $data .= '</soap12:Envelope>';
            //[Informational 1xx]
            $cCode['100']="Continue";
            $cCode['101']="Switching Protocols";
            //[Successful 2xx]
            $cCode['200']="OK";
            $cCode['201']="Created";
            $cCode['202']="Accepted";
            $cCode['203']="Non-Authoritative Information";
            $cCode['204']="No Content";
            $cCode['205']="Reset Content";
            $cCode['206']="Partial Content";
            //[Redirection 3xx]
            $cCode['300']="Multiple Choices";
            $cCode['301']="Moved Permanently";
            $cCode['302']="Found";
            $cCode['303']="See Other";
            $cCode['304']="Not Modified";
            $cCode['305']="Use Proxy";
            $cCode['306']="(Unused)";
            $cCode['307']="Temporary Redirect";
            //[Client Error 4xx]
            $cCode['400']="Bad Request";
            $cCode['401']="Unauthorized";
            $cCode['402']="Payment Required";
            $cCode['403']="Forbidden";
            $cCode['404']="Not Found";
            $cCode['405']="Method Not Allowed";
            $cCode['406']="Not Acceptable";
            $cCode['407']="Proxy Authentication Required";
            $cCode['408']="Request Timeout";
            $cCode['409']="Conflict";
            $cCode['410']="Gone";
            $cCode['411']="Length Required";
            $cCode['412']="Precondition Failed";
            $cCode['413']="Request Entity Too Large";
            $cCode['414']="Request-URI Too Long";
            $cCode['415']="Unsupported Media Type";
            $cCode['416']="Requested Range Not Satisfiable";
            $cCode['417']="Expectation Failed";
            //[Server Error 5xx]
            $cCode['500']="Internal Server Error";
            $cCode['501']="Not Implemented";
            $cCode['502']="Bad Gateway";
            $cCode['503']="Service Unavailable";
            $cCode['504']="Gateway Timeout";
            $cCode['505']="HTTP Version Not Supported";

            $tamanho = strlen($data);
            $parametros = array(
                'Content-Type: application/soap+xml;charset=utf-8;action="'.$namespace."/".$metodo.'"',
                'SOAPAction: "'.$metodo.'"',
                "Content-length: $tamanho");
            $aspas = '"';
            $oCurl = curl_init();
            if (is_array($this->aProxy)) {
                curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, 1);
                curl_setopt($oCurl, CURLOPT_PROXYTYPE, "CURLPROXY_HTTP");
                curl_setopt($oCurl, CURLOPT_PROXY, $this->aProxy['IP'].':'.$this->aProxy['PORT']);
                if ($this->aProxy['PASS'] != '') {
                    curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->aProxy['USER'].':'.$this->aProxy['PASS']);
                    curl_setopt($oCurl, CURLOPT_PROXYAUTH, "CURLAUTH_BASIC");
                } //fim if senha proxy
            }//fim if aProxy
            $certKeyFile = tmpfile();
            fwrite($certKeyFile, $this->certKEY);
            $certKeyPath = stream_get_meta_data($certKeyFile); //obt?m o caminho do arquivo
            $certKeyPath = $certKeyPath['uri'];
            $priKeyFile = tmpfile();
            fwrite($priKeyFile, $this->priKEY);
            $priKeyPath = stream_get_meta_data($priKeyFile);
            $priKeyPath = $priKeyPath['uri'];
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soapTimeout);
            curl_setopt($oCurl, CURLOPT_URL, $urlsefaz.'');
            curl_setopt($oCurl, CURLOPT_PORT, 443);
            curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
            curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabe�alho de resposta
            curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            //curl_setopt($oCurl, CURLOPT_SSLVERSION, 3);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2); // verifica o host evita MITM
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($oCurl, CURLOPT_SSLCERT, $certKeyPath);
            curl_setopt($oCurl, CURLOPT_SSLKEY, $priKeyPath);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $parametros);
            $xml = curl_exec($oCurl);
            $info = curl_getinfo($oCurl); //informa��es da conex�o
            fclose($certKeyFile);
            fclose($priKeyFile);
            $txtInfo ="";
            $txtInfo .= "URL=$info[url]\n";
            $txtInfo .= "Content type=$info[content_type]\n";
            $txtInfo .= "Http Code=$info[http_code]\n";
            $txtInfo .= "Header Size=$info[header_size]\n";
            $txtInfo .= "Request Size=$info[request_size]\n";
            $txtInfo .= "Filetime=$info[filetime]\n";
            $txtInfo .= "SSL Verify Result=$info[ssl_verify_result]\n";
            $txtInfo .= "Redirect Count=$info[redirect_count]\n";
            $txtInfo .= "Total Time=$info[total_time]\n";
            $txtInfo .= "Namelookup=$info[namelookup_time]\n";
            $txtInfo .= "Connect Time=$info[connect_time]\n";
            $txtInfo .= "Pretransfer Time=$info[pretransfer_time]\n";
            $txtInfo .= "Size Upload=$info[size_upload]\n";
            $txtInfo .= "Size Download=$info[size_download]\n";
            $txtInfo .= "Speed Download=$info[speed_download]\n";
            $txtInfo .= "Speed Upload=$info[speed_upload]\n";
            $txtInfo .= "Download Content Length=$info[download_content_length]\n";
            $txtInfo .= "Upload Content Length=$info[upload_content_length]\n";
            $txtInfo .= "Start Transfer Time=$info[starttransfer_time]\n";
            $txtInfo .= "Redirect Time=$info[redirect_time]\n";
            $txtInfo .= "Certinfo=".print_r($info['certinfo'], true)."\n";
            $lenN = strlen($xml);
            $posX = stripos($xml, "<");
            if ($posX !== false) {
                $xml = substr($xml, $posX, $lenN-$posX);
            } else {
                $xml = '';
            }
            $this->soapDebug = $data."\n\n".$txtInfo."\n".$xml;
            if ($xml === false || $posX === false) {
                //n�o houve retorno
                $msg = curl_error($oCurl);
                if ($info['http_code'] >= 100) {
                    $msg .= $info['http_code'].$cCode[$info['http_code']];
                }
                throw new nfephpException($msg);
            } else {
                //houve retorno mas ainda pode ser uma mensagem de erro do webservice
                if ($info['http_code'] > 300) {
                    $msg = $info['http_code'].$cCode[$info['http_code']];
                    $this->pSetError($msg);
                }
            }
            curl_close($oCurl);
            if ($info['http_code'] != 200) {
                $xml = '';
            }
            return $xml;
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
    } //fim sendSOAP

    /**
     * pGetNumLot
     * Obt�m o numero do �ltimo lote de envio
     *
     * @name pGetNumLot
     * @return numeric Numero do Lote
     */
    protected function pGetNumLot()
    {
        $lotfile = $this->raizDir.'config/numloteenvio.xml';
        $domLot = new DomDocument;
        $domLot->load($lotfile);
        $num = $domLot->getElementsByTagName('num')->item(0)->nodeValue;
        if (is_numeric($num)) {
            return $num;
        } else {
            //arquivo n�o existe, ent�o suponho que o numero seja 1
            return 1;
        }
    }//fim getNumLot

    /**
     * pPutNumLot
     * Grava o numero do lote de envio usado
     *
     * @name pPutNumLot
     * @param numeric $num Inteiro com o numero do lote enviado
     * @return boolean true sucesso ou FALSO erro
     */
    protected function pPutNumLot($num)
    {
        if (is_numeric($num)) {
            $lotfile = $this->raizDir.'config/numloteenvio.xml';
            $numLot = '<?xml version="1.0" encoding="UTF-8"?><root><num>'.$num.'</num></root>';
            if (! file_put_contents($lotfile, $numLot)) {
                //em caso de falha retorna falso
                $msg = "Falha ao tentar gravar o arquivo numloteenvio.xml.";
                $this->pSetError($msg);
                return false;
            }
        }
        return true;
    } //fim putNumLot

    /**
     * getUltNSU
     * Pega o ultimo numero NSU gravado no arquivo numNSU.xml
     *
     * @name getUltNSU
     * @param type $sigla sigla do estado (UF)
     * @param type $tpAmb tipo de ambiente 1-produ��o ou 2 homologa��o
     * @return mixed o numero encontrado no arquivo ou false em qualquer outro caso
     */
    private function pGetUltNSU($sigla = '', $tpAmb = '')
    {
        try {
            if ($sigla=='' || $tpAmb=='') {
                $msg = "Tanto a sigla do estado como o ambiente devem ser informados.";
                throw new nfephpException($msg);
            }
            $nsufile = $this->raizDir.'config/numNSU.xml';
            if (!is_file($nsufile)) {
                $msg = "O arquivo numNSU.xml n�o est� na pasta config/.";
                throw new nfephpException($msg);
            }
            //buscar o ultimo NSU no xml
            $xml = new SimpleXMLElement($nsufile, null, true);
            $searchString = '/NSU/UF[@sigla="'.$sigla.'" and @tpAmb="'.$tpAmb.'"]';
            $ufn = $xml->xpath($searchString);
            $ultNSU = (string) $ufn[0]->ultNSU[0];
            if ($ultNSU == '') {
                $ultNSU = '0';
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return $ultNSU;
    }//fim getUltNSU

    /**
     * putUltNSU
     * Grava o ultNSU fornecido pela SEFAZ
     *
     * @name putUltNSU
     * @param type $sigla sigla do estado (UF)
     * @param type $tpAmb tipo de ambiente
     * @param type $ultNSU Valor retornado da consulta a SEFAZ
     * @return boolean true gravado ou false falha
     */
    private function putUltNSU($sigla, $tpAmb = self::AMBIENTE_HOMOLOGACAO, $ultNSU = '')
    {
        try {
            if ($sigla=='' || $tpAmb=='' || $ultNSU=='') {
                $msg = "A sigla do estado, o tipo de ambiente e o numero do ultimo NSU s�o obrigat�rios.";
                throw new nfephpException($msg);
            }
            $nsufile = $this->raizDir.'config/numNSU.xml';
            if (!is_file($nsufile)) {
                $msg = "O arquivo numNSU.xml n�o est� na pasta config/.";
                throw new nfephpException($msg);
            }
            //buscar o ultimo NSU no xml
            $xml = new SimpleXMLElement($nsufile, null, true);
            $searchString = '/NSU/UF[@sigla="'.$sigla.'" and @tpAmb="'.$tpAmb.'"]';
            $ufn = $xml->xpath($searchString);
            if ($ufn[0]->ultNSU[0] != '') {
                $ufn[0]->ultNSU[0] = $ultNSU;
            }
            if (! file_put_contents($nsufile, $xml->asXML())) {
                $msg = "O arquivo n�o pode ser gravado na pasta config/.";
                throw new nfephpException($msg);
            }
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    }//fim putUltNSU

    /**
     * pTrata239
     * Esta fun��o corrige automaticamente todas as vers�es dos
     * webservices sempre que ocorrer o erro 238 ou 239
     * no retorno de qualquer requisi��o aos webservices
     *
     * @name pTrata239
     * @param string $xml xml retornado da SEFAZ
     * @param string $siglaUF sigla do estado
     * @param numeric $tpAmb tipo do ambiente
     * @param string $metodo m�todo
     */
    private function pTrata239($xml = '', $siglaUF = '', $tpAmb = '', $servico = '', $versaodefault = '')
    {
        //quando ocorre esse erro o que est� errado � a vers�o indicada no arquivo nfe_ws2.xml
        // para esse m�todo, ent�o nos resta ler o retorno pegar o numero correto da vers�o,
        // comparar com o default e caso sejam diferentes corrigir o arquivo nfe_ws2.xml
        try {
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if ($tpAmb == self::AMBIENTE_PRODUCAO) {
                $sAmbiente = 'producao';
            } else {
                //for�a homologa��o em qualquer outra situa��o
                $sAmbiente = 'homologacao';
            }
            //verifica se alguma conting�ncia est� habilitada
            if ($this->enableSVCAN) {
                $siglaUF = self::CONTINGENCIA_SVCAN;
            } elseif ($this->enableSVCRS) {
                $siglaUF = self::CONTINGENCIA_SVCRS;
            }
            //habilita verifica��o de erros
            libxml_use_internal_errors(true);
            //limpar erros anteriores que possam estar em mem�ria
            libxml_clear_errors();
            //carrega o xml de retorno com o erro 239
            $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            //recupera os erros da libxml
            $errors = libxml_get_errors();
            if (!empty($errors)) {
                //houveram erros no xml ou o arquivo n�o � um xml
                $msg = "O xml retornado possue erros ou n�o � um xml.";
                throw new nfephpException($msg, self::STOP_MESSAGE);
            }
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                    $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $versao= !empty($doc->getElementsByTagName('versaoDados')->item(0)->nodeValue) ?
                    $doc->getElementsByTagName('versaoDados')->item(0)->nodeValue : '';
            if (($cStat == '239' || $cStat == '238') && $versao != $versaodefault) {
                //realmente as vers�es est�o diferentes => corrigir
                $nfews = $this->raizDir.'config'.DIRECTORY_SEPARATOR.$this->xmlURLfile;
                if (is_file($nfews)) {
                    //carregar o xml com os webservices
                    $objxml = new SimpleXMLElement($nfews, null, true);
                    foreach ($objxml->UF as $objElem) {
                        //procura dados do UF
                        if ($objElem->sigla == $siglaUF) {
                            //altera o numero da vers�o
                            $objElem->$sAmbiente->$servico->attributes()->version = "$versao";
                            //grava o xml alterado
                            if (!file_put_contents($nfews, $objxml->asXML())) {
                                $msg = "A vers�o do servi�o $servico de $siglaUF [$sAmbiente] "
                                       ."no arquivo $nfews n�o foi corrigida.";
                                throw new nfephpException($msg, self::STOP_MESSAGE);
                            } else {
                                break;
                            }//fim file_put
                        }//fim elem UF
                    }//fim foreach
                }//fim is file
            }//fim cStat ver=ver
        } catch (nfephpException $e) {
            $this->pSetError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
        return true;
    }//fim trata 239


    /**
     * gunzip2
     * Descompacta strings GZIP usando arquivo tempor�rio
     *
     * @name gunzip2
     * @param string $data Dados compactados com gzip
     * @return string xml descompactado
     * @throws Exception
     */
    private function pGunzip2($data)
    {
        //cria um nome para o arquivo temporario
        do {
            $tempName = uniqid('temp ');
        } while (file_exists($tempName));
        //grava a string compactada no arquivo tempor�rio
        if (file_put_contents($tempName, $data)) {
            try {
                ob_start();
                //efetua a leitura do arquivo descompactando e jogando o resultado
                //bo cache
                @readgzfile($tempName);
                //descarrega o cache na vari�vel
                $uncompressed = ob_get_clean();
            } catch (Exception $e) {
                $ex = $e;
            }
            //remove o arquivo tempor�rio
            if (file_exists($tempName)) {
                unlink($tempName);
            }
            if (isset($ex)) {
                throw $ex;
            }
            //retorna a string descomprimida
            return $uncompressed;
        }
    }//fim gunzip2

    /**
     * gunzip1
     * Descompacta strings GZIP
     *
     * @name gunzip1
     * @param string $data Dados compactados com gzip
     * @return mixed
     */
    private function pGunzip1($data)
    {
        $len = strlen($data);
        if ($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
            $msg = "N�o � dado no formato GZIP.";
            $this->pSetError($msg);
            return false;
        }
        $method = ord(substr($data, 2, 1));  // metodo de compress�o
        $flags  = ord(substr($data, 3, 1));  // Flags
        if ($flags & 31 != $flags) {
            $msg = "N�o s�o permitidos bits reservados.";
            $this->pSetError($msg);
            return false;
        }
        // NOTA: $mtime pode ser negativo (limita��es nos inteiros do PHP)
        $mtime = unpack("V", substr($data, 4, 4));
        $mtime = $mtime[1];
        $headerlen = 10;
        $extralen  = 0;
        $extra     = "";
        if ($flags & 4) {
            // dados estras prefixados de 2-byte no cabe�alho
            if ($len - $headerlen - 2 < 8) {
                $msg = "Dados inv�lidos.";
                $this->pSetError($msg);
                return false;
            }
            $extralen = unpack("v", substr($data, 8, 2));
            $extralen = $extralen[1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                $msg = "Dados inv�lidos.";
                $this->pSetError($msg);
                return false;
            }
            $extra = substr($data, 10, $extralen);
            $headerlen += 2 + $extralen;
        }
        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inv�lidos.";
                $this->pSetError($msg);
                return false;
            }
            $filenamelen = strpos(substr($data, $headerlen), chr(0));
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                $msg = "Dados inv�lidos.";
                $this->pSetError($msg);
                return false;
            }
            $filename = substr($data, $headerlen, $filenamelen);
            $headerlen += $filenamelen + 1;
        }
        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data no cabe�alho
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inv�lidos.";
                $this->pSetError($msg);
                return false;
            }
            $commentlen = strpos(substr($data, $headerlen), chr(0));
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                $msg = "Formato de cabe�alho inv�lido.";
                $this->pSetError($msg);
                return false;
            }
            $comment = substr($data, $headerlen, $commentlen);
            $headerlen += $commentlen + 1;
        }
        $headercrc = "";
        if ($flags & 2) {
            // 2-bytes de menor ordem do CRC32 esta presente no cabe�alho
            if ($len - $headerlen - 2 < 8) {
                $msg = "Dados inv�lidos.";
                $this->pSetError($msg);
                return false;
            }
            $calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
            $headercrc = unpack("v", substr($data, $headerlen, 2));
            $headercrc = $headercrc[1];
            if ($headercrc != $calccrc) {
                $msg = "Checksum do cabe�alho falhou.";
                $this->pSetError($msg);
                return false;
            }
            $headerlen += 2;
        }
        // Rodap� GZIP
        $datacrc = unpack("V", substr($data, -8, 4));
        $datacrc = sprintf('%u', $datacrc[1] & 0xFFFFFFFF);
        $isize = unpack("V", substr($data, -4));
        $isize = $isize[1];
        // decompress�o
        $bodylen = $len-$headerlen-8;
        if ($bodylen < 1) {
            $msg = "BUG da implementa��o.";
            $this->pSetError($msg);
            return false;
        }
        $body = substr($data, $headerlen, $bodylen);
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
                case 8:
                    // Por hora somente � suportado esse metodo de compress�o
                    $data = gzinflate($body, null);
                    break;
                default:
                    $msg = "M�todo de compress�o desconhecido (n�o suportado).";
                    $this->pSetError($msg);
                    return false;
            }
        }  // conteudo zero-byte � permitido
        // Verificar CRC32
        $crc   = sprintf("%u", crc32($data));
        $crcOK = $crc == $datacrc;
        $lenOK = $isize == strlen($data);
        if (!$lenOK || !$crcOK) {
            $msg = ( $lenOK ? '' : 'Verifica��o do comprimento FALHOU. ').( $crcOK ? '' : 'Checksum FALHOU.');
            $this->pSetError($msg);
            return false;
        }
        return $data;
    }//fim gunzip1

    /**
     * convertTime
     * Converte o campo data/hora retornado pelo webservice em um timestamp unix
     *
     * @name convertTime
     * @param  string $DataHora Exemplo: "2014-03-28T14:39:54-03:00"
     * @return float
     */
    protected function pConvertTime($dataHora = '')
    {
        $timestampDH = 0;
        if ($dataHora) {
            $aDH = explode('T', $dataHora);
            $adDH = explode('-', $aDH[0]);
            $atDH = explode(':', substr($aDH[1], 0, 8));//substring para recuperar apenas a hora, sem o fuso hor�rio
            $timestampDH = mktime($atDH[0], $atDH[1], $atDH[2], $adDH[1], $adDH[2], $adDH[0]);
        }
        return $timestampDH;
    } //fim convertTime

    /**
     * pSplitLines
     * Divide a string do chave publica em linhas com 76 caracteres (padr�o original)
     *
     * @name pSplitLines
     * @param string $cnt certificado
     * @return string certificado reformatado
     */
    private function pSplitLines($cnt = '')
    {
        if ($cnt != '') {
            $cnt = rtrim(chunk_split(str_replace(array("\r", "\n"), '', $cnt), 76, "\n"));
        }
        return $cnt;
    }//fim splitLines

    /**
     * cleanString
     * Remove todos dos caracteres espceiais do texto e os acentos
     *
     * @name cleanString
     * @return  string Texto sem caractere especiais
     */
    private function pCleanString($texto)
    {
        $aFind = array('&','�','�','�','�','�','�','�','�','�','�','�','�',
            '�','�','�','�','�','�','�','�','�','�','�','�','�','�');
        $aSubs = array('e','a','a','a','a','e','e','i','o','o','o','u','u',
            'c','A','A','A','A','E','E','I','O','O','O','U','U','C');
        $novoTexto = str_replace($aFind, $aSubs, $texto);
        $novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/]/", "", $novoTexto);
        return $novoTexto;
    }//fim cleanString

    /**
     * pSetError
     * Adiciona descri��o do erro ao contenedor dos erros
     *
     * @name pSetError
     * @param   string $msg Descri��o do erro
     * @return  none
     */
    private function pSetError($msg)
    {
        $this->errMsg .= "$msg\n";
        $this->errStatus = true;
    }

    /**
     * ativaContingencia
     * Ativa a contingencia SVCAN ou SVCRS conforme a
     * sigla do estado
     * @param string $siglaUF
     * @return void
     */
    public function ativaContingencia($siglaUF = '')
    {
        if ($siglaUF == self::CONTINGENCIA_SVCAN || $siglaUF == self::CONTINGENCIA_SVCRS) {
            $siglaUF = '';
        }
        if ($siglaUF == '') {
            $ctg = $this->ctgList[$this->siglaUF];
        } else {
            $ctg = $this->ctgList[$siglaUF];
        }
        if ($ctg == self::CONTINGENCIA_SVCAN) {
            $this->enableSVCAN = true;
            $this->enableSVCRS = false;
        } elseif ($ctg == self::CONTINGENCIA_SVCRS) {
            $this->enableSVCAN = false;
            $this->enableSVCRS = true;
        }
    }

    /**
     * desativaContingencia
     * Desliga op��o de conting�ncia
     *
     * @return void
     */
    public function desativaContingencia()
    {
        $this->enableSVCAN = false;
        $this->enableSVCRS = false;
    }

    /**
     * Gera numero de lote com base em microtime
     * @return string
     */
    private function pGeraNumLote()
    {
        return substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, 15);
    }

    /**
     * pClearXml
     * Remove \r \n \s \t
     * @param string $xml
     * @param boolean $remEnc remover encoding
     * @return string
     */
    private function pClearXml($xml = '', $remEnc = false)
    {
        $retXml = $xml;
        if ($remEnc) {
            $retXml = str_replace('<?xml version="1.0"?>', '', $retXml);
            $retXml = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $retXml);
            $retXml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $retXml);
        }
        $retXml = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"", '', $retXml);
        $retXml = str_replace('default:', '', $retXml);
        $retXml = str_replace(':default', '', $retXml);
        $retXml = str_replace("\n", '', $retXml);
        $retXml = str_replace("\r", '', $retXml);
        $retXml = str_replace("\s", '', $retXml);
        $retXml = str_replace("\t", '', $retXml);
        return $retXml;
    }

    /**
     * pLoadServico
     * Monta o namespace e o cabecalho da comunica��o SOAP
     * @param string $servico Identifica��o do Servico
     * @param array $aURL Dados das Urls do SEFAZ
     * @return void
     */
    private function pLoadServico(
        $servico,
        $siglaUF,
        $tpAmb,
        &$cUF,
        &$urlservico,
        &$namespace,
        &$cabec,
        &$metodo,
        &$versao
    ) {
        $cUF = $this->cUFlist[$siglaUF];
        //verifica se alguma conting�ncia est� habilitada,
        //neste caso precisa recarregar os webservices
        if ($this->enableSVCAN) {
            $aURL = $this->pLoadSEFAZ($tpAmb, self::CONTINGENCIA_SVCAN);
        } elseif ($this->enableSVCRS) {
            $aURL = $this->pLoadSEFAZ($tpAmb, self::CONTINGENCIA_SVCRS);
        } else {
            // TODO fmertins 05/07/15: Roberto por favor revisar abaixo, deixei apenas comentado.
            //if ($siglaUF !== $this->siglaUF) {
            $aURL = $this->pLoadSEFAZ($tpAmb, 'AN'/*$siglaUF*/);
            //} else {
            //    $aURL = $this->aURL;
            //} // FIM TODO fmertins 05/07/15
        }
        //recupera��o da vers�o
        $versao = $aURL[$servico]['version'];
        //recupera��o da url do servi�o
        $urlservico = $aURL[$servico]['URL'];
        //recupera��o do m�todo
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do servi�o
        $operation = $aURL[$servico]['operation'];
        $namespace = sprintf("%s/wsdl/%s", $this->URLPortal, $operation);
        //montagem do cabe�alho da comunica��o SOAP
        $cabec = sprintf(
            '<nfeCabecMsg xmlns="%s"><cUF>%s</cUF>'
            . '<versaoDados>%s</versaoDados></nfeCabecMsg>',
            $namespace,
            $cUF,
            $versao
        );
    }
}
