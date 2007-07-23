<?php

/**
 * Box with communities options to edit configurations
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */

class AMBCommunityEdit extends AMColorBox {

	private $itens = array();
	protected $abandoned=false;
	private $community;

	public function __construct(AMCommunities $community) {
		global $_CMAPP;
		parent::__construct($_CMAPP['imlang_url']."/img_edicao_comunidade.gif",self::COLOR_BOX_BEGE);

		$this->community = $community;

	}

	public function addItem($item) {
		$this->itens[] = $item;
	}

	public function __toString() {
		global $_CMAPP, $_language;

		$aco = $this->community->getACO();
		$admin = $aco->testUserPrivilege($_SESSION['user']->codeUser,
		AMCommunities::PRIV_ADMIN);

		$add_user = $aco->testUserPrivilege($_SESSION['user']->codeUser,
		AMCommunities::PRIV_ADD_USERS);

		$add_proj = $aco->testUserPrivilege($_SESSION['user']->codeUser,
		AMCommunities::PRIV_ADD_PROJECTS);


		$urledit = $_CMAPP['services_url']."/communities/update.php?frm_codeCommunity=".$this->community->code;
		$urleditimage = $_CMAPP['services_url']."/communities/change_image.php?frm_codeCommunity=".$this->community->code;
		//$urlmembers = $_CMAPP['services_url']."/communities/managemembers.php?frm_codeCommunity=".$this->community->code;

		$urlinvite = $_CMAPP['services_url']."/communities/inviteusers.php?frm_codeCommunity=".$this->community->code;
		$urlproject = $_CMAPP['services_url']."/communities/tieproject.php?frm_codeCommunity=".$this->community->code;

		if($admin){
			parent::add("<a href=\"$urledit\" class =\"cinza\">&raquo; ".$_language['community_link_edit']."</a><br />");
			parent::add("<a href=\"$urleditimage\" class =\"cinza\">&raquo; ".$_language['community_link_edit_image']."</a><br />");
			//parent::add("<a href=\"$urlmembers\" class =\"cinza\">&raquo; ".$_language['community_link_members']."</a><br />");
		}
		if($admin || $add_user)
			parent::add("<a href=\"$urlinvite\" class =\"cinza\">&raquo; ".$_language['community_link_invite']."</a><br />");

		if($admin || $add_proj)
			parent::add("<a href=\"$urlproject\" class =\"cinza\">&raquo; ".$_language['community_link_project']."</a><br />");

		if(!$admin && !$add_proj && !$add_user)
			parent::add($_language['no_privileges']);

		return parent::__toString();

	}
}