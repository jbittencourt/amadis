<?

/**
 * Register project box template
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMProject
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */

class AMTCadProjBox extends AMTCadBox {

  public function __construct($titulo="") {
    parent::__construct();

  }

  public function setTitle($value) {
    global $_CMAPP;
    parent::setTitle("<img src=\"$_CMAPP[imlang_url]/$value\">");

  }

  public function __toString() {
    
    return parent::__toString();
    
  }


}



?>