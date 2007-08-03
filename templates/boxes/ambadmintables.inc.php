<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminTables extends AMColorBox { 

	public function __construct() {
    	global $_language;
    
    	parent::__construct($_language['regional_configuration'], AMColorBox::COLOR_BOX_BLUA);
  	}

  	public function __toString() {
    	global $_language, $_CMAPP;

    	parent::add("<a href=\"$_CMAPP[services_url]/admin/editStates.php\">$_language[edit_states]</a><br />");    
    	parent::add("<a href=\"$_CMAPP[services_url]/admin/editAreas.php\">$_language[edit_knowledge_areas]</a><br />");    
    	return parent::__toString();
  	}
}