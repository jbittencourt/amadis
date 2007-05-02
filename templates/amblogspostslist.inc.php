<?php

/**
 * List of the blogs in AMADIS.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMBlog
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMPageBox, AMTCadBox
 */
class AMBlogsPostsList extends AMPageBox {

	public function __construct() {
		parent::__construct(5);
	}


	public function init(CMContainer $list, $count) {
		$this->numItems = $count;
		$this->itens = $list;
	}

	public function __toString()
	{
		global $_language,$_CMAPP;

		$box = new AMTCadbox($_language['blogs_last_posts'], AMTCadBox::CADBOX_LIST,AMTCadBox::DIARY_THEME);
		$box->add("<table id=\"diary_list\" bgcolor='#F9F8FD'>");
		
		if(!$this->itens->__isEmpty()) {
			$i = 0;
			foreach($this->itens as $item) {
				
				$id = "diary_list_1";
				if(($i%2)==1) "diary_list_2";
				$i++;
				$box->add("<tr id=\"$id\" class=\"diary_list_line\">");

				$profile = "";
				$user = $item->user[0];
	//if the user has alredy filled their diary profile
				$test = $item->profile;
				if(!empty($test)) {
					$it = $item->profile->getIterator();
					$profile = $it->current();
				}

	//print the diary image or, if empty, the user image
				$box->add("<td>");

				$thumb = AMBlogImage::getThumb($item->user[0],$profile);
				$box->add($thumb->getView());
	

	//print the rest of the table
				$box->add("<td width=40%>");
				
				$link = "<a href=\"$_CMAPP[services_url]/blog/blog.php?frm_codeUser=$user->codeUser\" class=\"titpost\">";
				
				$box->add("$link $item->title</a>");

				$box->add('<br/>');

				$box->add(new AMTUserInfo($user));
				$box->add("</td>");
				$box->add("<td><span class='texto'>".date("$_language[hour_format] $_language[date_format]",$item->time)."</a></span></td\>");
				$box->add("</tr>");
			}
		}
		else {
			$box->add("<span class=\"texto\">$_language[no_diary_found]</span>");
		}

		$box->add("</table>");

		parent::add($box);
		return parent::__toString();
	}
}
