<?

class AMBPeopleLastPagesModified extends AMColorBox {

  private $itens;

  public function __construct() {
    global $_CMAPP;

    parent::__construct($_CMAPP['imlang_url']."/box_pessoas_pag_atualizadas.gif", self::COLOR_BOX_BEGE);
    $this->itens = AMLogUPloadFiles::getLastModifieds();
//     echo $this->itens[users];
//     echo $this->itens[projects];
//     notelastquery();die();
  }
  
  public function __toString() {
    global $_CMAPP, $_language;

    //paginas de usuarios
    if($this->itens['users']->__hasItems()) {
      parent::add("<b>&raquo;$_language[users_pages]</b><br>");
      
      foreach($this->itens['users'] as $item) {
	$urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=users/user_".$item->codeAnchor."&frm_codeUser=".$item->codeAnchor;
	parent::add(new AMTUserInfo($item->user[0]));
	parent::add("<a href='$urlpage' class='marrom'>($_language[see_home_page])</a><br>");
      }
      parent::add(new AMDotLine);
    }

    
    //paginas de projetos
    if($this->itens['projects']->__hasItems()) {
      parent::add("<b>&raquo;$_language[projects_pages]</b><br>");
      
      foreach($this->itens['projects'] as $item) {
	$urlpage  = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=projetos/projeto_";
	$urlpage .= $item->codeAnchor."&frm_codeProject=".$item->codeAnchor;

	$urlproj = "$_CMAPP[services_url]/projetos/projeto.php?frm_codProjeto=$item->codeAnchor";

	parent::add("<a href='$urlproj' class='blue'>".$item->project[0]->title."</a>&nbsp;");
	parent::add("<a href='$urlpage' class='marrom'>($_language[see_home_page])</a><br>");
      }
      //  parent::add(new AMDotLine);
    }
  
    
    return parent::__toString();
  }
}

?>