<?php
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMTreeAreas extends CMHTMLObj{

  private $list, $linhas;

  function __construct($lst){
    $this->list = $lst;
  }      


  function getTree($parent) {
    global $_CMAPP;
    
    $node = new AMTree($parent->nomArea);
    $hits = 0;
    for($i=0;$i < count($this->list->records);$i++) {
      $area = $this->list->records[$i];
      if($area->codPai==$parent->codArea) {
	$node->add($this->getTree($area));
	$node->add("<br>");
	$hits++;
      }
    }

    if($hits==0) {
      $node = "<a href=\"".$_CMAPP['tools_url']."/projects/projetoarea.php?frm_codArea=$parent->codArea\" class=\"fontgray\">&raquo; $parent->nomArea</a>";
    }

    return $node;

  }


  function __toString(){
    foreach($this->list->records as $area) {
      if($area->codPai==0) {
	parent::add($this->getTree($area));
	parent::add("<br>");
      }
    }

    parent::__toString();
  }
  
  
}