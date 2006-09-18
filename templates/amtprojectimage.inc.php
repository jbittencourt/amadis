<?

/**
 * Default vizualization of a project image.
 *
 * This class render a project image with the custom css.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage AMProject
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMImageTemplate, AMProjImage
 **/
class AMTProjectImage extends AMImageTemplate {
 

  public function __toString() {
    global $_CMAPP;

    $url = $this->getImageURL();
    parent::add("<img src=\"$url\" class=\"project_box\">");
    
    return parent::__toString();
  } 

}


?>