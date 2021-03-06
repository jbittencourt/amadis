<?php

/**
 * List tools of communities
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */

class AMBCommunityItems extends AMColorBox {

	private $link = array();

	public function __construct() {
		global $_CMAPP;
		parent::__construct($_CMAPP['imlang_url']."/img_itens_comunidade.gif",self::COLOR_BOX_LGREEN);
	}

	public function addLink($link, $url) {
		$this->links[] = array("url"=>$url,"link"=>$link);
	}


	public static function getForumButton($community){
		global $_CMAPP,$_language;

		$urlforum = $_CMAPP['services_url']."/communities/communityforums.php?frm_codeCommunity=".$community->code;

		return '<button class="community_items button-as-link" type="button" onclick="AM_openURL(\''.$urlforum.'\')">'
			 . '<img src="'.$_CMAPP['images_url'].'/icon_cm_forum_off.png"><br />'
			 . '<span class="project_items_text"> '.$_language['community_forumbutton'].'</span></button>';
	}

	public static function getChatButton($community){
		global $_CMAPP,$_language;

		$urlchat = $_CMAPP['services_url']."/communities/chat.php?frm_codeCommunity=".$community->code;

		return '<button class="community_items button-as-link" type="button" onclick="AM_openURL(\''.$urlchat.'\')">'
			 . '<img src="'.$_CMAPP['images_url'].'/icon_cm_chat_off.png"><br />'
			 . '<span class="project_items_text"> '.$_language['community_chatbutton'].'</span></button>';
	}

	public static function getLeaveButton($community){
		global $_CMAPP,$_language;

		$url = "";

		return '<button class="community_items button-as-link" type="button" onclick="if(confirm(\''.$_language['community_leave_confirm'].'\')) '
			 . '{ window.location=\''.$_SERVER['PHP_SELF'].'?frm_codeCommunity='.$community->code.'&co_action=A_leave\';  } else '
			 . '{ return false; }"><img src="'.$_CMAPP['images_url'].'/ico_cm_abandonar_off.gif"><br />'
			 . '<span class="project_items_text"> '.$_language['community_leavebutton'].'</span></button>';
	}

	public function doAction() {
		global $_CMAPP, $_language, $community;

		if(empty($_REQUEST['co_action'])){
			return false;
		}
		switch($_REQUEST['co_action']) {
			case "A_leave":
				$group = $community->getGroup();
				try {
					$group->retireMember($_SESSION['user']->codeUser);
				}
				catch(CMDBException $e) {
					$err = new CMError($_language['error_cannot_leave_community'],__CLASS__);
					return false;
				}
				CMHTMLPage::redirect($_CMAPP['services_url'].'/communities/community.php?frm_codeCommunity='.$_REQUEST['frm_codeCommunity'].'&frm_message=leave_community');
				$abandoned = true;

				//clear group cache
				if(!empty($_SESSION['amadis']['communities'])){
					unset($_SESSION['amadis']['communities']);
				}

				break;
		}
	}

	public function __toString() {

		global $_CMAPP,$community,$group, $_language;

		if(!empty($_REQUEST['co_action'])){
			$group = $community->getGroup();
			try {
				$group->retireMember($_SESSION['user']->codeUser);
			}
			catch(CMDBException $e) {
				$err = new CMError($_language['error_cannot_leave_community'],__CLASS__);
				return false;
			}
			CMHTMLPage::redirect($_CMAPP['services_url'].'/communities/community.php?frm_codeCommunity='.$_REQUEST['frm_codeCommunity'].'&frm_message=leave_community');
			$abandoned = true;

			//clear group cache
			if(!empty($_SESSION['amadis']['communities']))  unset($_SESSION['amadis']['communities']);
		}
		parent::add($this->getChatButton($community));
		parent::add($this->getForumButton($community));
		if(isset($_SESSION['user']))
		if($group->isMember($_SESSION['user']->codeUser))
		parent::add($this->getLeaveButton($community));

		return parent::__toString();

	}
}