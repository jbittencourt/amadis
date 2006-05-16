<?

/**
 * Thumbnail to a project
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMProject
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMImageTemplate
 */

class AMTProjectImage extends AMImageTemplate {
 

  public function __toString() {
    global $_CMAPP;

    $url = $this->getImageURL();
    parent::add("<img src=\"$url\" class=\"project_box\">");
    
    return parent::__toString();
  } 

}


?>