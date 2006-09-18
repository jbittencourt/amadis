<?
$_CMAPP['notrestricted'] = true;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray('communities','forum');

$pag = new AMTCommunities;
$pag->requires("forum.css",CMHTMLObj::MEDIA_CSS);

if(!empty($_REQUEST['frm_codeCommunity'])) {
     $community = new AMCommunities;
     $community->code = $_REQUEST['frm_codeCommunity'];
     try{
       $community->load();
       $aco= $community->getACO();
     }catch(CMDBNoRecord $e){
       $location  = $_CMAPP['services_url']."/communities/community.php?frm_amerror=community_not_exists";
       $location .= "&frm_codeCommunity=".$_REQUEST['frm_codeCommunity'];
       header("Location:$location");
     }
} else { 
  $_REQUEST['frm_amerror'] = "error_no_community_id";
  $pag->add("<br><div align=center><a href=\"".$_SERVER['HTTP_REFERER']."\" ");
  $pag->add("class=\"cinza\">".$_language[back]."</a></div><br>");
  echo $pag;
  die();
}

$canAdmin = $aco->testUserPrivilege($_SESSION['user']->codeUser,
				    AMCommunities::PRIV_ADMIN);
$canManageComm = $aco->testUserPrivilege($_SESSION['user']->codeUser,
					 AMCommunities::PRIV_MANAGE_COMMUNICATION);


if(!empty($_REQUEST['frm_action'])) {
  switch($_REQUEST['frm_action']) {
  case "A_create":
    if(!$canAdmin && !$canManageComm) {
      //the current user doesn't have privileges
      $pag->addError($_language['error_no_privileges']);
      $url = $_CMAPP['services_url'].'/communities/community.php?frm_codeCommunity='.$community->code;
      $pag->add("<br><div align=center><a href='$url' class='cinza'>");
      $pag->add($_language['back']."</a></div><br>");
      echo $pag;
      
      die();  
    }

    $title = "<div class=\"forum_project_title\">$_language[community_forum_create] $community->name</div>";

    $box = new AMColorBox($title,AMColorBox::COLOR_BOX_BEGE);
    $box->add("<br/>");

    $box->add("<FORM ACTION='$_SERVER[PHP_SELF]'>");
    $box->setWidth("500px");
    $box->add("<INPUT TYPE=hidden NAME=frm_codeCommunity value='$_REQUEST[frm_codeCommunity]'>");
    $box->add("<INPUT TYPE=hidden NAME=frm_action value='A_make'>");
    $box->add("$_language[frm_name] <INPUT TYPE=text NAME=frm_name VALUE='$_REQUEST[frm_name]'>");

    $box->add("<P>$_language[forum_privs]");
    $box->add("<br><input type=radio checked name='frm_forum_priv_model' value='partial'>$_language[community_forum_priv_partial]");
    $box->add("<br><input type=radio name='frm_forum_priv_model' value='open'>".$_language[community_forum_priv_open]);
    $box->add("<br><input type=radio name='frm_forum_priv_model' value='restricted'>$_language[community_forum_priv_restricted]");

    $box->add("<P style='text-align: right'><BUTTON TYPE=SUBMIT CLASS='image-button'><IMG SRC='$_CMAPP[imlang_url]/bt_criar_forum.gif'></BUTTON>");

    $box->add("</FORM>");

    $pag->add($box);
    echo $pag;
    die();
    break;
  case "A_make":
    if(!$canAdmin && !$canManageComm) {
      //the current user doesn't have privileges
      $pag->addError($_language['error_no_privileges']);
      $url = $_CMAPP['services_url'].'/communities/community.php?frm_codeCommunity='.$community->code;
      $pag->add("<br><div align=center><a href='$url' class='cinza'>");
      $pag->add($_language['back']."</a></div><br>");
      echo $pag;
      
      die();  
    }

    $forum = new AMForum;
    $forum->name = $_REQUEST[frm_name];
    $forum->creationTime = time();
    $forum->save();
    
    $link = new AMCommunityForum;
    $link->codeForum = $forum->code;
    $link->codeCommunity = $_REQUEST[frm_codeCommunity];
    $link->save();

    $aco = $forum->getACO();

    switch($_REQUEST['frm_forum_priv_model']) {
    case 'partial':
      $aco->addGroupPrivilege($community->codeGroup,AMForum::PRIV_ALL);
      $aco->addWorldPrivilege(AMForum::PRIV_VIEW);
      break;
    case 'open':
      $aco->addGroupPrivilege($community->codeGroup,AMForum::PRIV_ALL);
      $aco->addWorldPrivilege(AMForum::PRIV_VIEW);
      $aco->addWorldPrivilege(AMForum::PRIV_POST);
      break;
    case 'restricted':
      $aco->addGroupPrivilege($community->codeGroup,AMForum::PRIV_ALL);
      break;
    }


    $link = "$_CMAPP[services_url]/forum/forum.php?frm_codeForum=$forum->code";
    CMHtmlPage::redirect($link);
    die();

    break;

  }
}

$pag->add("<br><br>");
$forums = $community->listForums();

$title = "<div class=\"forum_project_title\">$_language[community_forum_title] $community->name</div>";

$box = new AMColorBox($title,AMColorBox::COLOR_BOX_BEGE);
$box->add("<br/>");
$box->add(new AMBForum($community->name, $forums));

$box->add("<br/>");

$box->add("<FORM ACTION='$_CMAPP[services_url]/communities/communityforums.php' METHOD=post>");
$box->add("<INPUT TYPE=HIDDEN NAME=\"frm_codeCommunity\" VALUE=\"$_REQUEST[frm_codeCommunity]\">");
$box->add("<INPUT TYPE=HIDDEN NAME=\"frm_action\" VALUE=\"A_create\">");

if($canAdmin || $canManageComm) {
  $box->add("<BUTTON type=SUBMIT class=\"image-button forum-submit-button\">");
  $box->add("<img src=\"$_CMAPP[imlang_url]/img_novo_forum2.gif\" >");
  $box->add("</BUTTON>");
}

$box->add("</FORM>");

$pag->add($box);


echo $pag;


?>