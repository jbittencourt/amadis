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
      $link  = '<a class="cinza" href="'.$_CMAPP['services_url'].'/projects/project.php?frm_codProjeto=';
      $link .= $item->codeProject.'">&raquo; '.$item->title.'</a><br />';
      $this->links[] = $link;
    }

  }

  public function __toString() {

    global $_CMAPP;
       
    $_language = $_CMAPP['i18n']->getTranslationArray("projects");

    /*
     *Buffering html of the box to output screen
     */
    $buffer  = '<div>';
    $buffer .= '<img src="'.$_CMAPP['imlang_url'].'/img_projetos_visitados.gif" alt="" /><br /><br />';
    if(!empty($this->links)) {
      foreach($this->links as $item) {
	$buffer .= $item;
      }
    }
    $buffer .= '<a href="'.$_CMAPP['services_url'].'/projects/listprojects.php" class="green">&raquo; '.$_language['list_all_projects'].'</a><br />';
    $buffer .= '</div>';

    parent::add($buffer);

    return parent::__toString();

  }
}

?>
