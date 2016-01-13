<?php
/**
 * Este arquivo � parte do projeto NFePHP - Nota Fiscal eletr�nica em PHP.
 *
 * Este programa � um software livre: voc� pode redistribuir e/ou modific�-lo
 * sob os termos da Licen�a P�blica Geral GNU como � publicada pela Funda��o
 * para o Software Livre, na vers�o 3 da licen�a, ou qualquer vers�o posterior.
 * e/ou
 * sob os termos da Licen�a P�blica Geral Menor GNU (LGPL) como � publicada pela
 * Funda��o para o Software Livre, na vers�o 3 da licen�a, ou qualquer vers�o posterior.
 *
 * Este programa � distribu�do na esperan�a que ser� �til, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia expl�cita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUA��O PARA UM PROP�SITO EM PARTICULAR,
 * veja a Licen�a P�blica Geral GNU para mais detalhes.
 *
 * Voc� deve ter recebido uma c�pia da Licen�a Publica GNU e da
 * Licen�a P�blica Geral Menor GNU (LGPL) junto com este programa.
 * Caso contr�rio consulte
 * <http://www.fsfla.org/svnwiki/trad/GPLv3>
 * ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * @package     NFePHP
 * @name        CommonNFePHP.class.php
 * @version     1.0.1
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2014 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Fernando Mertins <fernando dot mertins at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *
 * Esta classe contem funcionalidades referentes � instancia��o e manipula��o de DOM Document
 */

class DomDocumentNFePHP extends DOMDocument
{

    /**
     * construtor
     * Executa o construtor-pai do DOMDocument e por padr�o define o XML sem espa�os
     * e sem identa��o
     * @param string $sXml Conte�do XML opcional a ser carregado no DOM Document.
     * @return void
     */
    public function __construct($sXml = NULL)
    {
        parent::__construct('1.0', 'utf-8');
        $this->formatOutput = false;
        $this->preserveWhiteSpace = false;
        
        if (is_string($sXml)) {
            $this->loadXML($sXml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        }
    }
}