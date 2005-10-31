<?

class AMMainMenu extends CMHtmlObj {
  private $itens;

  public function __construct() {
    global $_CMAPP;
    parent::__construct();

    //adiciona o item "inicial" no menu principal (se o usuario estiver logado)
    $this->addItem($_CMAPP['url']."/index.php",
		   array($_CMAPP['imlang_url']."/ico_inicial_off.gif",
			 $_CMAPP['imlang_url']."/ico_inicial_on.gif",)
		   );
    $this->addItem($_CMAPP['services_url']."/projetos/projects.php",
		   array($_CMAPP['imlang_url']."/ico_projetos_off.gif",
			 $_CMAPP['imlang_url']."/ico_projetos_on.gif",)
		   );
    
    $this->addItem($_CMAPP['services_url']."/people/people.php",
		   array($_CMAPP['imlang_url']."/ico_pessoas_off.gif",
			 $_CMAPP['imlang_url']."/ico_pessoas_on.gif",)
		   );
    $this->addItem($_CMAPP['services_url']."/communities/communities.php",
		   array($_CMAPP['imlang_url']."/ico_comunidades_off.gif",
			 $_CMAPP['imlang_url']."/ico_comunidades_on.gif",)
		   );
    
    /**if(!empty($_SESSION['user'])) {
      $this->addItem($_CMAPP['services_url']."/admin/admin.php",
		     array($_CMAPP['imlang_url']."/ico_administracao_off.gif",
			   $_CMAPP['imlang_url']."/ico_administracao_on.gif",)
		     );
    }**/

  }

  function addItem($link,$im) {

    if(is_array($im)) {
      $temp = new CMWSwapImage($link,$im[0],$im[1]);
      $temp->setTarget('_top');
    }
    else {
      $temp ="<a href=\"$item[link]\"><img src=\"$item[imagem]\"></a>";
    }

    
    $this->itens[] = $temp;
  }

  public function __toString() {
    global $_CMAPP;
    
    if(!empty($this->itens)) {
      parent::add("<table valign=bottom cellspacing=0 cellpadding=0><tr>");

      $menu = array();
      foreach($this->itens as $k=>$item) {
	$temp ="<td>";
	if($item instanceof CMHTMLObj) {
	  $temp.= $item->__toString();
	}
	else {
	  $temp.= $item;
	}
	$menu[]= $temp."</td>";
	
      }

      parent::add(implode("<td valign=bottom><img src=\"".$_CMAPP['images_url']."/menu_separador.gif\"></td>",$menu));
      
      parent::add("</tr></table>");

    }

    return parent::__toString();

  }

}



?>