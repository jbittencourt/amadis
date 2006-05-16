<?
/**
 * Abstract box, base of the box templates
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see CMHTMLObj
 */
abstract class AMAbstractBox extends CMHTMLObj {

  public $width;


  public function setWidth($w) {
    $this->width = $w;
  }



}

?>