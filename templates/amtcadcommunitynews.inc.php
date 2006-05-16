<?

/**
 * Template to Communities news
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Cristiano S. Basso <csbasso@lec.ufrgs.br>
 */

class AMTCadCommunityNews extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("azul2");

    $this->setImgId($_CMAPP['imlang_url']."/top_cadastro_comunidade.gif");

  }
}



?>