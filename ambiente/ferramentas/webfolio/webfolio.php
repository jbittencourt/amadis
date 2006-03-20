<?
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("webfolio");

/**
 *CACHE WEBFOLIO
 */
if(!isset($_SESSION['amadis']['webfolio'])) $_SESSION['amadis']['webfolio'] = array();

$pag = new AMTWebfolio(AMTWebfolio::WEBFOLIO_MY_WEBFOLIO);
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

//box for user rejects and accepts

// $resp = new AMBUserResponses;
// if($resp->__hasInvitations()) { 
//   $pag->add($resp);
// }

$box = new AMTwoColsLayout;
//$box->add("Teste 2",AMTwoColsLayout::RIGHT);


$foto = $_SESSION['user']->foto;
if(!empty($foto)) {
  $box->add(new AMTUserImage($foto),AMTwoColsLayout::LEFT);
}

$box->add("<p><font class=\"texto\"><b>".$_SESSION['user']->name."<br>".date($_language['date_format'],$_SESSION['user']->datNascimento)."</b>", AMTwoColsLayout::LEFT);

$box->add("<br>".$_SESSION['user']->email, AMTwoColsLayout::LEFT);

$box->add("</font>", AMTwoColsLayout::LEFT);


$box->add("<p><a href=\"$_CMAPP[services_url]/webfolio/changedata.php\" class=\"blue\">$_language[change_personal_data]</a>", AMTwoColsLayout::LEFT);

$box->add("<br><a href=\"$_CMAPP[services_url]/webfolio/changepassword.php\" class=\"blue\">$_language[change_password]</a><p>", AMTwoColsLayout::LEFT);

//foruns that the user participate

$forums = $_SESSION['user']->listLastModifiedForums();

$box->add(new AMBForunsParticipate($forums),
	  AMTwoColsLayout::LEFT);

//box de mensagens no correio
//$box->add(new AMBMailMessages, AMTwoColsLayout::LEFT);

$box->add("<br>", AMTwoColsLayout::LEFT);
$box->add(new AMBUserMessages, AMTwoColsLayout::LEFT);


//$box->add("<br>", AMTwoColsLayout::LEFT);
//$box->add(new AMBChatsUser, AMTwoColsLayout::LEFT);



$box->add(new AMBMyWebfolio, AMTwoColsLayout::RIGHT);
$box->add("<br>", AMTwoColsLayout::RIGHT);
//box de avisos
$box->add(new AMBAvisos(), AMTwoColsLayout::RIGHT);
$box->add("<br>", AMTwoColsLayout::RIGHT);

//box de novidades
$box->add(new AMBAmadisNews, AMTwoColsLayout::RIGHT);

$pag->add($box);
echo $pag;

?>
