<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

 class AMBPeopleLastDiaryPosts extends AMColorBox implements CMActionListener {

 	private $itens;

 	public function __construct() {
 		global $_CMAPP;

 		parent::__construct($_CMAPP['imlang_url']."/box_pessoas_diarios_atualiz.gif", self::COLOR_BOX_BEGE);

 	}
 	
 	public function doAction() {
 		$this->itens = $_SESSION['environment']->listLastDiaryPosts();

 	}

 	public function __toString() {
 		global $_CMAPP, $_language;

 		parent::add("<b>&raquo;$_language[last_blog_posts]</b><br>");
 		
 		if(!empty($this->itens)) {
 			foreach($this->itens as $item) {
 				//TODO Solve the problem with the page scroll in the blog
 				//parent::add("<a class=\"people_blog_entry\" href=\"$_CMAPP[services_url]/blog/blog.php?frm_codePost=$item->codePost#anchor_post_".$item->codePost."\">");
 				parent::add("<a class=\"people_blog_entry\" href=\"$_CMAPP[services_url]/blog/blog.php?frm_codePost=$item->codePost\">");
 				parent::add("&raquo; ".$item->title."</a>:  (");
 				parent::add(new AMTUserInfo($item->author[0],AMTUserInfo::LIST_USERNAME));
 				parent::add(", ".date($_language['date_format'],$item->time).")");
 				parent::add("<br>");
 			}
 		}
 		else{
 			parent::add($_language['no_diary_updated']);
 		}
 		parent::add("<img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='7' border='0'>");
 		parent::add(new AMDotLine);
 		parent::add("<a class=\"grape\" href=\"$_CMAPP[services_url]/blog/list.php\">");
 		parent::add("&raquo; $_language[list_all_diaries]</a>");
 		return parent::__toString();
 	}
 }