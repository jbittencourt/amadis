<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminConfig extends AMSimpleBox { 

  public function __construct() {
    global $_language;
    
    parent::__construct($_language['configuration']);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    parent::add($_language['edit_tools']."<br>");
    
    parent::add("<a href=\"$_CMAPP[services_url]/admin/configenvironment.php\">$_language[config_environment]</a><br>");    
    parent::add("<a href=\"$_CMAPP[services_url]/admin/configforum.php\">$_language[config_forum]</a><br>");
    parent::add("<a href=\"$_CMAPP[services_url]/admin/configchat.php\">$_language[config_chat]</a><br>");
    
    return parent::__toString();
  }

}