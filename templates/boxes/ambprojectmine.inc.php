<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectMine extends AMColorBox {
  
  private $itens = array();
    
  public function __construct() {
    global $_CMAPP;
    parent::__construct($_CMAPP['imlang_url']."/box_editar_projetos.gif",self::COLOR_BOX_BLUEB);
    $this->itens = $_SESSION['user']->listMyProjects();
  }
    
  public function __toString() {
    
    global $_CMAPP, $_language;
    
    
    /*    
     *Buffering html of the box to output screen
     */
    if(!empty($this->itens->items)) {
      foreach($this->itens as $item) {
	
	$url = $_CMAPP['services_url']."/projects/projeto.php?frm_codProjeto=".$item->codeProject;
    
	parent::add("<a href=\"$url\" class=\"green\">&raquo; ".$item->title."</a><br>");
      }
    }else parent::add("$_language[project_not_projects]");
      
    return parent::__toString();
      
  }
}