<?php
/**
 * This a second mainMenu, that used in a viewpage.php
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMUpload
 * @category AMBox
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMMainMenu
 */
class AMViewPageMenu extends CMHTMLObj {
	private $itens;
	private $secundaryItems;

	public function __construct() {
		global $_CMAPP;
		parent::__construct();

		//adiciona o item "inicial" no menu principal (se o usuario estiver logado)
		$this->addItem($_CMAPP['url']."/index.php",
			array($_CMAPP['imlang_url']."/wf_mn_inicial_off.gif",
					$_CMAPP['imlang_url']."/wf_mn_inicial_on.gif",)
		);
		$this->addItem($_CMAPP['services_url']."/projects/projects.php",
			array($_CMAPP['imlang_url']."/wf_mn_projetos_off.gif",
					$_CMAPP['imlang_url']."/wf_mn_projetos_on.gif",)
		);
		$this->addItem($_CMAPP['services_url']."/people/people.php",
			array($_CMAPP['imlang_url']."/wf_mn_pessoas_off.gif",
					$_CMAPP['imlang_url']."/wf_mn_pessoas_on.gif",)
		);
		$this->addItem($_CMAPP['services_url']."/communities/communities.php",
			array($_CMAPP['imlang_url']."/wf_mn_comunidades_off.gif",
					$_CMAPP['imlang_url']."/wf_mn_comunidades_on.gif",)
		);

		if(isset($_REQUEST['frm_codeUser']) && !empty($_REQUEST['frm_codeUser'])) {
			$check = (($_REQUEST['frm_codeUser'] == $_SESSION['user']->codeUser) ? true : false);
			if($check) {
				$this->addSecundaryItem($_CMAPP['services_url']."/upload/upload.php?frm_upload_type=user",
					array($_CMAPP['imlang_url']."/wf_mn_editpage_off.gif",
							$_CMAPP['imlang_url']."/wf_mn_editpage_on.gif",)
				);
			}
			
			$diario = ($_REQUEST['frm_codeUser'] != $_SESSION['user']->codeUser ? "?frm_codeUser=$_REQUEST[frm_codeUser]" : "");

			$this->addSecundaryItem($_CMAPP['services_url']."/blog/blog.php".$diario,
				array($_CMAPP['imlang_url']."/wf_mn_diario_off.gif",
						$_CMAPP['imlang_url']."/wf_mn_diario_on.gif")
				);
			$url = ($_REQUEST['frm_codeUser'] == $_SESSION['user']->codeUser ? $_CMAPP['services_url']."/webfolio/webfolio.php" : $_CMAPP['services_url']."/webfolio/userinfo_details.php?frm_codeUser=$_REQUEST[frm_codeUser]");
      		$this->addSecundaryItem($url,
				array($_CMAPP['imlang_url']."/wf_mn_webfolio_off.gif",
						$_CMAPP['imlang_url']."/wf_mn_webfolio_on.gif",)
			    );
			
    	} elseif(isset($_REQUEST[frm_codProjeto]) && !empty($_REQUEST[frm_codProjeto])) {
    
    		$this->addSecundaryItem($_CMAPP['services_url']."/upload/upload.php?frm_upload_type=project&frm_codeProjeto=$_REQUEST[frm_codProjeto]",
				array($_CMAPP['imlang_url']."/wf_mn_editpage_off.gif",
						$_CMAPP['imlang_url']."/wf_mn_editpage_on.gif",)
			);
			    
    		$url = $_CMAPP['services_url']."/projects/projectforums.php?frm_codeProject=$_REQUEST[frm_codProjeto]";
    		$this->addSecundaryItem($url,
			    array($_CMAPP['imlang_url']."/wf_mn_forum_off.gif",
				  		$_CMAPP['imlang_url']."/wf_mn_forum_on.gif",)
			    );
    		$url = $_CMAPP['services_url']."/projects/chat.php?frm_codeProject=$_REQUEST[frm_codProjeto]";
    		$this->addSecundaryItem($url,
			    array($_CMAPP['imlang_url']."/wf_mn_chat_off.gif",
						$_CMAPP['imlang_url']."/wf_mn_chat_on.gif",)
			    );
    	}
  }
  
  function addSecundaryItem($link, $im) {
    if(is_array($im)) {
      $temp = new CMWSwapImage($link,$im[0],$im[1]);
      $temp->setTarget('_top');
    }
    else {
      $temp ="<a href=\"$item[link]\"><img src=\"$item[imagem]\"></a>";
    }
    $this->secundaryItems[] = $temp;
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
	} else {
	  $temp.= $item;
	}
	$menu[]= $temp."</td>";
      }


      $secundaryMenu = array();
      if(isset($_REQUEST[frm_codeUser])) 
	$secundaryMenu[] = "<td valign=bottom><img src='$_CMAPP[images_url]/wf_icon_user.gif'></td>";

      if(isset($_REQUEST[frm_codProjeto]))
	$secundaryMenu[] = "<td valign=bottom><img src='$_CMAPP[images_url]/wf_icon_project.gif'></td>";

      foreach($this->secundaryItems as $item) {
	$temp = "<td>";
	if($item instanceof CMHTMLObj) {
	  $temp .= $item->__toString();
	} else {
	  $temp .= $item;
	}
	$secundaryMenu[] = $temp."</td>";
      }

      
      parent::add(implode("<td valign=bottom><img src=\"".$_CMAPP['images_url']."/wf_traco.gif\"></td>",$menu));
      parent::add("<td width='20'>&nbsp;</td>");
      parent::add(implode("<td valign=bottom><img src='$_CMAPP[images_url]/wf_traco_black.gif'></td>", $secundaryMenu));

      parent::add("<td width='20'>&nbsp;</td>");
      parent::add("<td width='20'><a href='$_SERVER[HTTP_REFERER]'><img src='$_CMAPP[imlang_url]/wf_icon_voltar.gif'></a></td>");
      parent::add("</tr></table>");
      
    }
    
    return parent::__toString();
    
  }
  
}