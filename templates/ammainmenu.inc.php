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

  public function __construct() {
    global $_CMAPP;
    parent::__construct();
/*
    //adiciona o item "inicial" no menu principal (se o usuario estiver logado)
    $this->addItem($_CMAPP['url']."/index.php",
		   array($_CMAPP['imlang_url']."/ico_inicial_off.gif",
			 $_CMAPP['imlang_url']."/ico_inicial_on.gif",)
		   );
    $this->addItem($_CMAPP['services_url']."/projects/projects.php",
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
    */
//     if(!empty($_SESSION['user'])) {
//       $this->addItem($_CMAPP['services_url']."/admin/admin.php",
// 		     array($_CMAPP['imlang_url']."/ico_administracao_off.gif",
// 			   $_CMAPP['imlang_url']."/ico_administracao_on.gif",)
// 		     );
//     }

  }



  public function __toString() {
    global $_CMAPP, $_language;
    

    parent::add("<ul id ='menufontExtra' class='menufont'><li><a href=\"".$_CMAPP['services_url']."/communities/communities.php\">".strtoupper($_language[communities])."</a></li><li><a href=\"".$_CMAPP['services_url']."/people/people.php\">".strtoupper($_language[people])."</a></li><li>OFICINAS</li><li>WEBFÓLIO</li><li>FERRAMENTAS</li></ul>");
	

    return parent::__toString();

  }

}