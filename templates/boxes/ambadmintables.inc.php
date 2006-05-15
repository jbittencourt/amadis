<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminTables extends AMSimpleBox { 

  public function __construct() {
    global $_language;
    
    parent::__construct($_langage[edit_tables]);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    parent::add($_language['edit_tables']."<Br>");
    
    parent::add("<a href=\"$_CMAPP[services_url]/admin/editar_estados.php\">$_language[edit_states]</a><br>");    
    parent::add("<a href=\"$_CMAPP[services_url]/admin/editar_areas.php\">$_language[edit_knowledge_areas]</a><br>");
    //parent::add("<a href=\"$_CMAPP[services_url]/admin/editprojectstatus.php\">$_language[edit_project_status]</a><br>");
    
    return parent::__toString();
  }

}

?>