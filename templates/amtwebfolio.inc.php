<?
/**
 * Template for the webfolio.
 *
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMTWebfolio extends AMMain {
  
  	const WEBFOLIO_DEFAULT = "top_webfolio.gif"; 
  	function __construct() {
    	global $_CMAPP, $_language;
    	parent::__construct("webfolio");
    
    	$this->requires("webfolio.css",CMHTMLObj::MEDIA_CSS);
    	$this->setImgId($_CMAPP['images_url'].'/ico_webfolio.gif');

    	if(isset($_REQUEST['frm_codeUser']) && !empty($_REQUEST['frm_codeUser'])) {
			$this->setSectionTitle($_language['webfolio']);
		} else {
			$this->setSectionTitle($_language['my_webfolio']);
		}
  	}
}
?>