<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminConfigEvents extends AMColorBox { 

  public function __construct() {
    global $_language;
    
    parent::__construct($_language['event'], AMColorBox::COLOR_BOX_BLUE);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
  
    parent::add("<a href=\"$_CMAPP[services_url]/admin/sendmessages.php\">$_language[send_messages]</a><br />");    
  
    return parent::__toString();
  }

}