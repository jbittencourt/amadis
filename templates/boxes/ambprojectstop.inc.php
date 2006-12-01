<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectsTop extends CMHTMLObj {

  private $links = array();
 
  public function __construct() {
    global $_CMAPP;
    parent::__construct();
    $res = $_SESSION['environment']->listTopProjects();
    foreach($res as $item) {
      $link  = "<a class=\"cinza\" href=\"".$_CMAPP['services_url']."/projects/projeto.php?frm_codProjeto=";
      $link .= $item->codeProject."\">&raquo; ".$item->title."</a><br>";
      $this->links[] = $link;
    }

  }

  public function __toString() {

    global $_CMAPP;
       
    $_language = $_CMAPP['i18n']->getTranslationArray("projects");

    /*
     *Buffering html of the box to output screen
     */
    $buffer  = "<table cellspacing=0 cellpadding=0 border=0>";
    $buffer .= "<tr><td>";
    $buffer .= "<img src=\"".$_CMAPP['imlang_url']."/img_projetos_visitados.gif\" border=\"0\"><br>";
    $buffer .= "<img src=\"".$_CMAPP['images_url']."/dot.gif\" border=\"0\" height=\"7\" width=\"1\"><br>";
    if(!empty($this->links)) {
      foreach($this->links as $item) {
	$buffer .= $item;
      }
    }
    $buffer .= "<a href=\"".$_CMAPP['services_url']."/projects/listprojects.php\" class=\"green\">&raquo; $_language[list_all_projects]</a><br>";
    $buffer .= "</td>";
    $buffer .= "</tr>";
    $buffer .= "</table>";

    parent::add($buffer);

    return parent::__toString();

  }
}

?>
