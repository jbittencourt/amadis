<?

/**
 * Thumbnail community template
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMImageTemplate
 */

class AMTCommunityImage extends AMImageTemplate {
 

  public function __toString() {
    global $_CMAPP;

    $url = $this->getImageURL();
    parent::add("<img src=\"$url\" class=\"boxcomunidade\">");
    
    return parent::__toString();
  } 

}


?>