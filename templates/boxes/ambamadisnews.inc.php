<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAmadisNews extends AMColorBox {

	private $nProjects;
	private $nCommunities;

	public function __construct() {
		global $_CMAPP;
		parent::__construct("$_CMAPP[imlang_url]/box_novidades_amadis.gif", AMColorBox::COLOR_BOX_BEGE);

		$this->nProjects = $_SESSION['user']->listNewsProjects();
		$this->nCommunities = $_SESSION['user']->listNewsCommunities();
		$this->_hasItems = true;
	}

	public function __toString() {
		global $_CMAPP, $_language;

		$hasItems = $this->nCommunities->__hasItems() || $this->nProjects->__hasItems();
		$news = false;
		if($hasItems) {
			if($this->nProjects->__hasItems()) {
				$news = true;
				parent::add("<b>&raquo; $_language[projects]</b><br>");
				foreach($this->nProjects as $item) {
					parent::add("<a class=\"cinza\" ");
					parent::add("href=\"$_CMAPP[services_url]/projetos/listprojects.php?list_action=A_list_news&frm_codProjeto=$item->codeProject#project_news_".$item->news[0]->codeNews."\">");
					parent::add("&raquo; ".$item->news[0]->title." - <b>($item->title)</b>");
					parent::add("</a><br>");
				}
				parent::add(new AMDotLine);
			}
				
			if($this->nCommunities->__hasItems()) {
				$news = true;
				parent::add("<b>&raquo; $_language[communities]</b><br>");
				foreach($this->nCommunities as $item) {
					parent::add("<a class=\"cinza\" ");
					parent::add("href=\"$_CMAPP[services_url]/communities/listcommunities.php?list_action=A_list_news&frm_codeCommunity=$item->code#community_news_".$item->news[0]->code."\">");
					parent::add("&raquo; ".$item->news[0]->title." - <b>($item->name)</b>");
					parent::add("</a><br>");
				}
				parent::add(new AMDotLine);
			}
		}
		
		if(!$news) parent::add("&nbsp;&nbsp;$_language[no_amadis_news]");

		return parent::__toString();

	}
}

?>