<?
/**
 * AMACORender
 * @ignore
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMACO
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see CMHTMLObj
 */
class AMAddressBook extends CMPage {


  function __construct() {

    $this->requires("amadis.css.php","CSS");
    $this->requires("addressbook.js");
    $this->setMargin(0,0,0,0);
  }


}


?>