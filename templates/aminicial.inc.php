<?
/**
 * Template of the inicial page
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMMain
 */

class AMInicial extends AMMain {
  
  	function __construct() 
  	{
   		global $_CMAPP, $_language;
    	parent::__construct('initial');

    	$this->setImgId($_CMAPP['images_url']."/ico_initial.gif");
    	$this->setSectionTitle($_language['presentation']);
    	
	}
}