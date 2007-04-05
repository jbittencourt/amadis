<?php

/**
 * Main navigation menu, localized in horizontal bar
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMXOMenu extends CMHtmlObj {
  	private $itens;

  	public function __construct() {
    	global $_CMAPP;
    	parent::__construct();

    	if(isset($_SESSION['user'])){
	    	//adiciona o item "inicial" no menu principal (se o usuario estiver logado)
    		//$this->addItem($_CMAPP['url']."/index.php", 'begin');
    	}
		
    	
    	if(!empty($_SESSION['user'])) {
			$this->addItem($_CMAPP['services_url']."/webfolio/webfolio.php", $_CMAPP['images_url'].'/xo/icon_webfolio_off.png', 'webfolio');    		
    	}
    	$this->addItem($_CMAPP['services_url']."/people/people.php", $_CMAPP['images_url'].'/xo/icon_pessoas_off.png', 'people');
	    $this->addItem($_CMAPP['services_url']."/communities/communities.php", $_CMAPP['images_url'].'/xo/icon_comunidades_off.png', 'communities');
    	$this->addItem($_CMAPP['services_url']."/projects/projects.php", $_CMAPP['images_url'].'/xo/icon_projetos_off.png', 'projects');	    
		
//     if(!empty($_SESSION['user'])) {
//       $this->addItem($_CMAPP['services_url']."/admin/admin.php",
// 		     array($_CMAPP['imlang_url']."/ico_administracao_off.gif",
// 			   $_CMAPP['imlang_url']."/ico_administracao_on.gif",)
// 		     );
//     }

  	}


	public function addItem($url, $img, $label)
	{
		global $_language;
		$this->items[] = "<li><a href='".$url."'><img onmousover=\"alert(this.src);\" src='".$img."' border='0' alt='".ucfirst($_language[$label])."'></a></li>";
	}
	
  	public function __toString() {
    	global $_CMAPP, $_language;
    
		parent::add('<ul id="menu_items">');
    	if(empty($_CMAPP['user'])) {
			parent::add('<li><a href="javascript:void(0);" id="login"><img src="'.$_CMAPP['images_url'].'/xo/icon_abertura_off.png" alt="" border="0" /></a></li>');
			parent::addPageEnd(parent::getScript('Event.observe("login", "click", showLogin);'));
    	} else {
    		parent::add('<li><a href="'.$_CMAPP['url'].'"><img src="'.$_CMAPP['images_url'].'/xo/icon_abertura_on.png" alt="" border="0" /></a></li>');
    	}
    	if(!empty($this->items)) {
    		foreach($this->items as $item) {
    			parent::add($item);
    		}
    	}
		
    	if(!empty($_SESSION['user'])) {
    		parent::add('<li><a href="javascript:void(0);" id="menu"><img src="'.$_CMAPP['images_url'].'/xo/icon_menu_on.png" alt="" border="0" /></a></li>');
    	}
    	parent::add("</ul>");
		
    	parent::addPageEnd(parent::getScript('Event.observe("menu", "click", switchMenu);
			Event.observe("close-button", "click", lock);')
		);
    	
    	return parent::__toString();

  	}

}