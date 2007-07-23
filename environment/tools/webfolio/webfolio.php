<?php
/**
 * User's Webfolio.
 * 
 * This script provides a visualization of the user's webfolio by it's owner.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package AMADIS
 * @subpackage Core
 * @category AMVisualization
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>, Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMTWebfolio, AMTwoColsLayout, AMBuserFriendInvitations, AMBuserInvitations, AMBAvisos, AMBAmadisNews, AMTuserImage
 */
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("webfolio");

/**
 *CACHE WEBFOLIO
 */
if(!isset($_SESSION['amadis']['webfolio'])) $_SESSION['amadis']['webfolio'] = array();

$pag = new AMTWebfolio;
$box = new AMTwoColsLayout;

$pag->add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=20 height=20>");

//caixa para adicionar amigos
$pag->add("<div id='friends_invitation'");

$inv = new AMBUserFriendInvitations;
if($inv->__hasInvitations()) {
	$pag->add($inv);
}
$pag->add("</div>");

//caixa de convites de projetos
$inv = new AMBUserInvitations;
if($inv->__hasInvitations()) {
	$pag->add($inv);
}

//caixa de convites de comunidades
$inv = new AMBCommunitiesInvitations;
if($inv->__hasInvitations()) {
	$pag->add($inv);
}

$box = new AMTwoColsLayout;

/*********************
 * LEFT COLUMN
 ********************/
$foto = AMUserPicture::getImage($_SESSION['user']);
if($foto == AMUserPicture::DEFAULT_IMAGE) {
	$box->add(new AMTUserImage(AMUserPicture::DEFAULT_IMAGE, AMImageTemplate::METHOD_DEFAULT), AMTwoColsLayout::LEFT);
} else $box->add(new AMTUserImage($foto),AMTwoColsLayout::LEFT);

$box->add("<p><span class=\"texto\"><b>". $_SESSION['user']->name."<br />" . date($_language['date_format'],(integer) $_SESSION['user']->birthDate) . "</b>"
, AMTwoColsLayout::LEFT);

$box->add("<br />".$_SESSION['user']->email, AMTwoColsLayout::LEFT);

$box->add("</font>", AMTwoColsLayout::LEFT);


$box->add("<p><a href='$_CMAPP[services_url]/webfolio/changedata.php' class='blue'>$_language[change_personal_data]</a>",
AMTwoColsLayout::LEFT);
$box->add("<br /><a href='$_CMAPP[services_url]/webfolio/changePicture.php' class='blue'>$_language[change_picture]</a>",
AMTwoColsLayout::LEFT);
$box->add("<br /><a href='$_CMAPP[services_url]/webfolio/changepassword.php' class='blue'>$_language[change_password]</a><p>",
AMTwoColsLayout::LEFT);

$box->add("<br />", AMTwoColsLayout::LEFT);
//foruns that the user participate
$forums = $_SESSION['user']->listLastModifiedForums();

$box->add(new AMBForunsParticipate($forums),
AMTwoColsLayout::LEFT);


//Chats in that are happening or in the blog of
//the  projects or communities that the user participates
$box->add("<br />", AMTwoColsLayout::LEFT);
$box->add(new AMBChatsUser, AMTwoColsLayout::LEFT);


/*********************
 * RIGHT COLUMN
 ********************/
$box->add(new AMBMyWebfolio, AMTwoColsLayout::RIGHT);
$box->add("<br />", AMTwoColsLayout::RIGHT);
//box de avisos
$box->add(new AMBAvisos(), AMTwoColsLayout::RIGHT);
$box->add("<br />", AMTwoColsLayout::RIGHT);

//box de novidades
$box->add(new AMBAmadisNews, AMTwoColsLayout::RIGHT);

$box->add("<br />", AMTwoColsLayout::LEFT);
$box->add(new AMBUserMessages, AMTwoColsLayout::RIGHT);


$pag->add($box);
echo $pag;