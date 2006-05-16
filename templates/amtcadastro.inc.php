<?

/**
 * Template to register user page
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMTCadastro extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("azul2");

    $this->setImgId($_CMAPP['imlang_url']."/top_cadastro.gif");

    $this->openNavMenu();

    $this->requires('webfolio.css',self::MEDIA_CSS);
    $this->requires("cadastro.js.php",self::MEDIA_JS);
  }
}



?>