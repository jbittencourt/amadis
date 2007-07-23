<?php
require_once 'Calendar' . DIRECTORY_SEPARATOR . 'Day.php';

/**
 * List of all blogs
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @category AMVisualization
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMDiaryList, AMAmbiente
 */

$_CMAPP['notrestricted'] = true;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("blog");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

if(isset($_REQUEST['frm_date'])) {
	$_SESSION['blog_posts_list_type'] = $_REQUEST['frm_date'];
}

switch($_SESSION['blog_posts_list_type']) {
	case 'week':
		$lday = new Calendar_Day(date('Y'), date('m'), date('d'));
		$fday = $lday;
		for($i=0;$i<7;$i++) $fday = $fday->prevDay('object');
		$lday->build();
		$fday->build();
		
		$ftemp = $fday->fetchAll();
		$ltemp = $lday->fetchAll();
		
		$lowerDate = $ftemp[0]->getTimestamp();
		$upperDate = $ltemp[23]->getTimestamp();
		
		break;
	case 'month':
		$lday = new Calendar_Day(date('Y'), date('m'), date('d'));
		$fday = $lday;
		for($i=0;$i<30;$i++) $fday = $fday->prevDay('object');
		$lday->build();
		$fday->build();
		
		$ftemp = $fday->fetchAll();
		$ltemp = $lday->fetchAll();
		
		$lowerDate = $ftemp[0]->getTimestamp();
		$upperDate = $ltemp[23]->getTimestamp();
		break;	
	case 'day':
	default:
		$day = new Calendar_Day(date('Y'), date('m'), date('d'));
		$day->build();
		$temp = $day->fetchAll();
		$lowerDate = $temp[0]->getTimestamp();
		$upperDate = $temp[23]->getTimestamp();
		break;									
}



$pag = new AMTBlog;
$pag->setRSSFeed($_CMAPP['services_url'].'/blog/listPostsRSS.php','teste');


//creates filter box
$sbox = new AMColorBox($_language['blog_posts_select'], AMColorBox::COLOR_BOX_ROSA);
$sbox->add('<form action="listPosts.php">');
$sbox->add('<input type=hidden name=action value=A_filter />');

//creates a function to return the current selected option
$f = create_function('$d', 'if($_SESSION["blog_posts_list_type"]==$d) return "selected";');

$sbox->add('<p>' . $_language['blog_filter_date'] . '&nbsp; <select name="frm_date">');
$sbox->add('<option value="today" '. $f('today') . '>' . $_language['blog_filter_day'] );
$sbox->add('<option value="week" '. $f('week') . '> ' . $_language['blog_filter_week'] );
$sbox->add('<option value="month" ' . $f('month') . '> ' . $_language['blog_filter_month'] );
$sbox->add('</select></p>');

$sbox->add("<p>$_language[person] &nbsp;<input name=frm_user type=text value='$_REQUEST[frm_user]'/></p>");
$sbox->add("<p>$_language[subject] &nbsp;<input name=frm_subject type=text value='$_REQUEST[frm_subject]'/></p>");

$sbox->add("<p><input type=submit value='$_language[search]'/></p>");
$sbox->add('</form>');
$pag->add($sbox);

$box = new AMBlogsPostsList;

$extra_filters = array('user' => $_REQUEST['frm_user'],
					   'subject' => $_REQUEST['frm_subject']
						);
						
$items = AMEnvironment::listBlogsPosts($lowerDate, $upperDate, $extra_filters, $box->getInitial(), $box->getFinal());
$box->init($items['data'],$items['count']);
$pag->add($box);

echo $pag; 