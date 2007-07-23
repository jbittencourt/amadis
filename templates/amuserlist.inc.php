<?php
/**
 * @Package AMADIS
 * @subpackage AMBoxes
 */

class AMUserList extends AMTCadBox {

	const PEOPLE = 0;
	const PROJECT = 1;
	const COMMUNITY = 2;

	protected $itens;
	protected $class_prefix;



	public function __construct(CMContainer $items,$title,$theme, $type=AMTCadBox::CADBOX_SEARCH) {
		global $_language;

		switch($theme) {
			case self::PEOPLE:
				$box_theme=AMTCadBox::PEOPLE_THEME;
				$this->class_prefix = 'people';
				break;
			case self::PROJECT:
				$box_theme=AMTCadBox::PROJECT_THEME;
				$this->class_prefix = 'project';
				break;
			case self::COMMUNITY:
				$box_theme=AMTCadBox::COMMUNITY_THEME;
				$this->class_prefix = 'community';
				break;
		}

		parent::__construct($title, $type, $box_theme);
		$this->itens = $items;
	}


	public function __toString() {
		global $_language,$_CMAPP;

		parent::add("<br /><br />");

		parent::add("<table id=\"".$this->class_prefix."_list\">");

		if(!empty($this->itens->items)) {
			$i = 0;
			foreach($this->itens as $item) {
				$id = $this->class_prefix."_list_1";
				if(($i%2)==1) $id = $this->class_prefix."_list_2";
				$i++;
				parent::add("<tr id=\"$id\" class=\"".$this->class_prefix."_list_line\">");
				parent::add("<td width = '80'>");

				$thumb = AMUserPicture::getThumb($item);
				parent::add($thumb->getView());

				parent::add("<td>");
				parent::add(new AMTUserInfo($item));
				parent::add("</td>");
				parent::add("<td width='90'>".AMMain::getAddFriendButton($item->codeUser));
				parent::add("<td width='90'>".AMMain::getViewPageButton($item->codeUser));
				parent::add("<td width='90'>".AMMain::getViewDiaryButton($item->codeUser));
				parent::add("</tr>");

				
				if($item->isVariableDefined('request') && !empty($item->request)) {
					$req = $item->request[0];
					parent::add("<tr id=\"$id\" class=\"".$this->class_prefix."_list_line_int_int\">");
					parent::add("<td align='left' valign='top' colspan = '5'><br /><font class='project_list_subtitle'>".$_language['join_date']."</font>");
					parent::add(date($_language['date_format'],$req->timeResponse)."</td>");
					parent::add("</tr>");


					parent::add("<tr id=\"$id\" class=\"".$this->class_prefix."_list_line_int\">");
					parent::add("<td align='left' valign='top' colspan = '5'><br /><font class='project_list_subtitle'>$_language[join_reason]</font>");
					parent::add("<br /><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='12' border='0'>");
					parent::add($req->textRequest."</td>");
					parent::add("</tr>");

					parent::add("<tr id=\"$id\" class=\"".$this->class_prefix."_list_line\">");
					parent::add("<td align='left' valign='top' colspan = '5'><br /><font class='project_list_subtitle'>".$_language[approval]."</font>");
					parent::add("<br />".$req->textResponse."</td>");
					parent::add("</tr>");
				}

				parent::add("<tr><td><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='8' border='0'></td></tr>");
			}
		}
		else {
			parent::add("<span class=\"texto\">$_language[no_user_found]</span>");
		}

		parent::add("</table>");


		return parent::__toString();
	}
}