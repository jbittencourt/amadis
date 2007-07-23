<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminTables extends AMSimpleBox { 

  public function __construct() {
    global $_language;
    
    parent::__construct($_language['edit_tables']);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    parent::add($_language['edit_tables']."<Br>");
    
    parent::add("<a href=\"$_CMAPP[services_url]/admin/editStates.php\">$_language[edit_states]</a><br />");    
    parent::add("<a href=\"$_CMAPP[services_url]/admin/editAreas.php\">$_language[edit_knowledge_areas]</a><br />");    
    
    parent::add("<br />".$_language['view_logs']."<Br>");
    parent::add("<a href=\"$_CMAPP[services_url]/admin/viewlogs.php\">$_language[view_logs]</a>");
    return parent::__toString();
  }

}