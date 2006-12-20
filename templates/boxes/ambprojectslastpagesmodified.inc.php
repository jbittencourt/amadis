<?php

class AMBProjectsLastPagesModified extends AMColorBox {

  private $itens;

  public function __construct() {
    global $_CMAPP;

    parent::__construct($_CMAPP['imlang_url']."/box_projetos_pag_atualizada.gif", self::COLOR_BOX_BEGE);
    $this->itens = AMLogUPloadFiles::getLastModifieds();

  }
  
  public function __toString() {
    global $_CMAPP, $_language;
  
    //paginas de projetos
    if($this->itens['projects']->__hasItems()) {
      parent::add("<b>&raquo;$_language[projects_pages]</b><br>");
      
      foreach($this->itens['projects'] as $item) {
	$urlpage  = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=projetos/projeto_";
	$urlpage .= $item->codeAnchor."&frm_codeProject=".$item->codeAnchor;

	$urlproj = "$_CMAPP[services_url]/projects/project.php?frm_codProjeto=$item->codeAnchor";

	parent::add("<a href='$urlproj' class='marinho'>".$item->project[0]->title."</a>&nbsp;");
	parent::add("<a href='$urlpage' class='projBlue'>($_language[see_home_page])</a><br>");
      }
      parent::add("<img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='7' border='0'>");
      parent::add(new AMDotLine);

      parent::add("<img src='$_CMAPP[images_url]/icon_projetos.gif'> <a href='".$_CMAPP['services_url']."/pages/listpages.php?frm_type=projects' class='projBlue'>".$_language['see_all_pages']."</a>");
    }
  
    
    return parent::__toString();
  }
}