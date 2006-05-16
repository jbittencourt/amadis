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

class AMTCadProj extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("azul2");

    $this->setImgId($_CMAPP['imlang_url']."/top_cadastro_projetos.gif");


  }
}



?>