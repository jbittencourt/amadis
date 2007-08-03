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
class AMMainMenu extends CMHtmlObj {
  	private $itens;

  	public function __construct() 
  	{
    	global $_CMAPP;
    	parent::__construct();

    	if(isset($_SESSION['user'])){
	    	//adiciona o item "inicial" no menu principal (se o usuario estiver logado)
    		$this->addItem($_CMAPP['url']."/index.php", 'begin');
    	}
    	$this->addItem($_CMAPP['services_url'].'/projects/projects.php', 'projects');
	    $this->addItem($_CMAPP['services_url'].'/people/people.php', 'people');
	    $this->addItem($_CMAPP['services_url'].'/communities/communities.php', 'communities');

     	/*if(!empty($_SESSION['user'])) {
       		$this->addItem($_CMAPP['services_url'].'/admin/admin.php', 'administration');
     	}*/
 	}


	public function addItem($url, $label)
	{
		global $_language;
		$this->items[] = "<li><a href='".$url."'>".strtoupper($_language[$label])."</a></li>";
	}
	
	public function __toString() 
	{
    	global $_CMAPP, $_language;
    

    	parent::add('<ul id ="menufontExtra" class="menufont">');
    	
    	if(!empty($this->items)) {
    		foreach($this->items as $item) {
    			parent::add($item);
    		}
    	}
		parent::add('</ul>');

    	return parent::__toString();
  	}

}