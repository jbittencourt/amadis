<?php

/**
 * List of communities with paging
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMPageBox, CMActionListener
 */

class AMBCommunitiesList extends AMPageBox implements CMActionListener {

	private $items = array();
	protected $title;
	protected $box_type;

	public function __construct($items='', $title='') {
		parent::__construct(10);
		$this->items = $items;
		$this->title = $title;
	}

	public function doAction() {
		global $_language;

  		if(!isset($_REQUEST['search_action'])){
			$_REQUEST['search_action'] = "";
  		}

		switch($_REQUEST['search_action']) {
			default:
				$resul = $_SESSION['environment']->listAllCommunities($this->init, $this->numHitsFP);
				$this->box_type = AMTCadBox::CADBOX_LIST;
				$this->title = $_language['communities_on_amadis'];
				break;
			case "listing":
				$resul = $_SESSION['environment']->searchCommunities($_REQUEST['frm_search'], $this->init, $this->numHitsFP);
				$this->box_type = AMTCadBox::CADBOX_SEARCH;
				$this->title = $_language['search_communities_result']." <font color=red>$_REQUEST[frm_search]</font>";
				break;
		}
		$this->itens = $resul[0];
		$this->numItems = $resul['count'];
	}

	public function __toString() {
		global $_language, $_CMAPP;

		$box = new AMTCadBox("", $this->box_type, AMTCadBox::COMMUNITY_THEME);


		$box->add('<table id="community_list">');

		if($this->items->__hasItems()) {
			$i = 0;
			foreach($this->items as $item) {
				$id = "community_list_1";
				if(($i%2)==1) $id = "community_list_2";
				$i++;

				$box->add("<tr id='$id' class=\"community_list_line\"><td>");

				try { 
					$thumb = AMCommunityImage::getThumb($item);
				} catch(CMException $e) {
					/**
					 * @TODO correct exception
					 */
					note($e); 
				}
				//note($thumb->getView()); die();
				$box->add($thumb->getView());

				$box->add('<td style="width: 20%">');
				$box->add("<a href=\"".$_CMAPP['services_url']."/communities/community.php?frm_codeCommunity=".$item->code);
				$box->add("\" class=\"cinza\">".$item->name."</a><br />");
				$box->add("</td><td class=\"texto\">".nl2br($item->description)."</td>");
				$box->add("<td style=\"width: 20%\"><a href=\"$_CMAPP[services_url]/communities/community.php?frm_codeCommunity=".$item->code."\" class=\"blue\">");
				$box->add("$_language[community_visit]</a></td></tr>");
			}
		}
		else {
			$box->add("<span class='texto'>$_language[no_communities_found]</a>");
		}
		$box->add('</table>');
		$box->setTitle($this->title);
		//parent::add($box);

		return $box->__toString();
	}
}