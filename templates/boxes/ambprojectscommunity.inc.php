<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectsCommunity extends CMHTMLObj {

  private $communities = array();

  public function __construct() {

    parent::__construct();
    $this->communities = $_SESSION['environment']->listCommunities();

  }
  
  public function __toString() {

	global $_CMAPP;
    
    	$_language = $_CMAPP['i18n']->getTranslationArray("projects");

    	/*
     	 * URL de submit do form
     	 */
    	$url = $_CMAPP['services_url']."/projects/listprojects.php";
    
    	/*
     	 *Buffering html of the box to output screen
     	 */

    	$buffer .= '<form action="'.$url.'" method="post">';
    	$buffer .= '<div><img src="'.$_CMAPP['imlang_url'].'/img_projetos_comunidade.gif" alt="" /><br />';
    	$buffer .= '<select onchange="document.frm_prjtCommunity.submit();" ';
    	$buffer .= 'name="frm_codeCommunity" style="position: relative; top: 0pt;">';
    	$buffer .= '<option selected="selected">['.$_language['select_one'].']</option>';

    	if($this->communities->__hasItems()) {
      		foreach($this->communities as $item) {
				$buffer .= '<option value="'.$item->code.'">'.$item->name.'</option>';
      		}
    	}

    	$buffer .= "</select>\n";
    	$buffer .= '<input type="hidden" name="list_action" value="A_list_communities" />';
    	$buffer .= '<br /><br /><span class="textoint">&raquo; '.$_language['projects_community'].'</span>';
    	$buffer .= '</div></form>';

    	parent::add($buffer);

    	return parent::__toString();
	}
}