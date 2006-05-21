<?
 /**
 * @package AMADIS
 * @subpackage AMColorBox
 */

class AMBPeopleLastPagesModified extends AMColorBox {

  private $itens;

  public function __construct() {
    global $_CMAPP;

    parent::__construct($_CMAPP['imlang_url']."/box_pessoas_pag_atualizadas.gif", self::COLOR_BOX_BEGE);
    $this->itens = AMLogUPloadFiles::getLastModifieds();

  }
  
  public function __toString() {
    global $_CMAPP, $_language;

    //paginas de usuarios
    if($this->itens['users']->__hasItems()) {
      parent::add("<b>&raquo;$_language[users_pages]</b><br>");
      
      foreach($this->itens['users'] as $item) {
	$urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=users/user_".$item->codeAnchor."&frm_codeUser=".$item->codeAnchor;
	parent::add(new AMTUserInfo($item->user[0]));
	parent::add("<a href='$urlpage' class='cereja'>($_language[see_home_page])</a><br>");
      }
      parent::add("<img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='7' border='0'>");
      parent::add(new AMDotLine);

      parent::add("<img src='$_CMAPP[images_url]/icon_user_window.gif'> <a href='".$_CMAPP['services_url']."/pages/listpages.php' class='cereja'>".$_language['see_all_pages']."</a>");
    }  
    
    return parent::__toString();
  }
}

?>