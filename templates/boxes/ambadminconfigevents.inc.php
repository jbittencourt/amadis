<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminConfigEvents extends AMSimpleBox { 

  public function __construct() {
    global $_language;
    
    parent::__construct($_language['configuration']);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    parent::add($_language['event']."<br />");
    
    parent::add("<a href=\"$_CMAPP[services_url]/admin/sendmessages.php\">$_language[send_messages]</a><br />");    
  
    return parent::__toString();
  }

}