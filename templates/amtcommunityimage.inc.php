<?

/**
 * Default vizualization of a community images.
 * 
 * This class render a community picture with the custom css.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto,AMTCommunityImage
 **/
class AMTCommunityImage extends AMImageTemplate {
 

  public function __toString() {
    global $_CMAPP;
    
    $url = $this->getImageURL();
    parent::add("<img src=\"$url\" class=\"boxcomunidade\">");
    
    return parent::__toString();
  } 

}


?>