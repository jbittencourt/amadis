<?

/**
 * @ignore
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMCorreio extends AMMain {
  

  function __construct() {
    global $urlimagens, $urlimlang;
    parent::__construct("azul");


    $this->setImgId("$urlimlang/img_tit_correio.gif");
  }
}



?>