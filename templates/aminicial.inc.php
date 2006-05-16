<?

/**
 * Template of the inicial page
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMMain
 */

class AMInicial extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("green");

    $this->setImgId($_CMAPP['imlang_url']."/top_apresentacao.gif");

  }
}



?>