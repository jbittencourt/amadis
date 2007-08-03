<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminConfig extends AMColorBox { 

  	public function __construct() {
    	global $_language;
    
    	parent::__construct($_language['configuration'], AMColorBox::COLOR_BOX_GREEN);
  	}

  	public function __toString() {
    	global $_language, $_CMAPP;
    
    	parent::add("<a href=\"$_CMAPP[services_url]/admin/configenvironment.php\">$_language[config_environment]</a><br />");    
    	parent::add("<a href=\"$_CMAPP[services_url]/admin/configforum.php\">$_language[config_forum]</a><br />");
    	parent::add("<a href=\"$_CMAPP[services_url]/admin/configchat.php\">$_language[config_chat]</a><br />");
    
    	return parent::__toString();
  	}
}