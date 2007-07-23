<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectItems extends AMColorBox {
  
  	private $link = array();
    
  	public function __construct() {
    	global $_CMAPP;
    	parent::__construct($_CMAPP['imlang_url']."/box_itens_projeto.gif",self::COLOR_BOX_BLUA);
  	}
    
  	public function addLink($link, $url) {
    	$this->links[] = array("url"=>$url,"link"=>$link);
  	}
  
  	public static function getPageButton($proj){
    	global $_CMAPP,$_language;

    	$urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=projects/project_".$proj."&frm_codProjeto=".$proj;
    	$button  = "<button id='page' class='project_items button-as-link' type='button' onclick=\"AM_openURL('$urlpage')\">";
    	$button .= "<img src='$_CMAPP[images_url]/dot.gif' height='25px;' width='10px'><br />";
    	$button .= "<span class='project_items_text'> $_language[project_link_pagebutton]</span>";
    	$button .= "</button>";
    	return $button;
  	}

  	public static function getWikiButton($proj){
    	global $_CMAPP,$_language;
    
    	$urlwiki = $_CMAPP['services_url']."/wiki/index.php?frm_namespace=project_".$proj;
    	$button  = "<button id='wiki' class='project_items button-as-link' type='button' onclick=\"AM_openURL('$urlwiki')\">";
    	$button .= "<img src='$_CMAPP[images_url]/dot.gif' height='25px;'><br />";
    	$button .= "<span class='project_items_text'> $_language[project_link_wikibutton]</span>";
    	$button .= "</button>";
    	return $button;
  	}
  
  	public static function getForumButton($proj){
    	global $_CMAPP,$_language;
    
    	$urlforum = $_CMAPP['services_url']."/projects/projectforums.php?frm_codeProject=".$proj;
    	$button  = "<button id='forum' class='project_items button-as-link' type='button' onclick=\"AM_openURL('$urlforum')\">";
    	$button .= "<img src='$_CMAPP[images_url]/dot.gif' height='25px;'><br />";
    	$button .= "<span class='project_items_text'> $_language[project_link_forumbutton]</span>";
    	$button .= "</button>";
    	return $button;
  	}

  	public static function getChatButton($proj){
    	global $_CMAPP,$_language;
	    
    	$urlchat = $_CMAPP['services_url']."/projects/chat.php?frm_codeProject=".$proj;
    	$button  = "<button id='chat' class='project_items button-as-link' type='button' onclick=\"AM_openURL('$urlchat')\">";
    	$button .= "<img src='$_CMAPP[images_url]/dot.gif' height='25px;'><br />";
    	$button .= "<span class='project_items_text'> $_language[project_link_chatbutton]</span>";
    	$button .= "</button>";
    	return $button;
  	}

  	public static function getDiaryButton($proj){
		global $_CMAPP,$_language;
	    
    	$urldiary = $_CMAPP['services_url']."/agregator/agregator.php?frm_codeProject=".$proj;
    	$button  = "<button id='diary' class='project_items button-as-link' type='button' onclick=\"AM_openURL('$urldiary')\">";
    	$button .= "<img src='$_CMAPP[images_url]/dot.gif' height='25px;'><br />";
    	$button .= "<span class='project_items_text'> $_language[project_link_blogbutton]</span>";
    	$button .= "</button>";
    	return $button;
  	}


  	public function __toString() {
    
    	global $_CMAPP,$proj, $_language;   
    
        parent::add($this->getPageButton($proj->codeProject));
    	parent::add($this->getDiaryButton($proj->codeProject));
    	parent::add($this->getForumButton($proj->codeProject));
    	parent::add('<br /><br />');
    	parent::add($this->getChatButton($proj->codeProject));
    	parent::add($this->getWikiButton($proj->codeProject));
      
    	return parent::__toString();
  	}

}