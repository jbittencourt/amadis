<?
/**
 * A simple dot line
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMDotLine extends CMHtmlObj {
 
  	private $fullWidth;

  	const FULL_WIDTH = TRUE;

  	public function __construct($fullWidth=true) {
    	parent::__construct();
    	$this->fullWidth = $fullWidth;
  	}

  	public function __toString() {
    	global $_CMAPP;
    
    	if($this->fullWidth) {
      		parent::add('<div id="dashed-line"></div>');
      		//parent::add('<img src="'.$_CMAPP['images_url'].'/dot.gif" width="2" alt="" /></div>');
    	} else {
      		parent::add('<img src="'.$_CMAPP['images_url'].'/box_traco.gif" alt="" />');
    	}
    	return parent::__toString();
  	}
}
?>