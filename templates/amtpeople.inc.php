<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */


class AMTPeople extends AMMain {

	function __construct() 
  	{
    
    	global $_CMAPP, $_language;
    	
    	parent::__construct('people');

    	$this->setImgId($_CMAPP['images_url']."/ico_people.gif");
    	$this->requires("people.css",CMHTMLObj::MEDIA_CSS);
		$this->setSectionTitle($_language['people']);

  	}
}