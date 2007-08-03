<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminUsers extends AMColorBox {

	public function __construct() {
		global $_language;
		parent::__construct($_language['edit_people_and_groups'], AMColorBox::COLOR_BOX_YELLOW);
	}

	public function __toString() {
		global $_language, $_CMAPP;
		$conteudo = "";
		$conteudo .= '<a href="#">'.$_language['edit_groups'].'</a><br />';
		$conteudo .= "<a href='$_CMAPP[services_url]/admin/editUser.php'>".$_language['edit_people']."</a><br />";
		//$conteudo .= $_language['edit_group'];
		parent::add($conteudo);
		return parent::__toString();
	}

}