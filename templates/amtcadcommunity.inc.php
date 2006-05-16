<?

/**
 * Register Community template
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */

class AMTCadCommunity extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("azul2");

    $this->setImgId($_CMAPP['imlang_url']."/top_cadastro_comunidade.gif");


  }
}



?>