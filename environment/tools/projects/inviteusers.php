<?php
/**
 * This page is used to invite users to a project.
 *
 * The objectiva of this page is the allow project members to
 * invite new users to their projects. The invitation is stored
 * in AMProjectMemberInvitation objects, and must be confirmed
 * by the user before they become part of the project. The system
 * check if the users is alredy part of the project, and in this
 * case don't show it.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @todo Don't display a users alredy invited in the list.
 **/

include("../../config.inc.php");


$_language = $_CMAPP['i18n']->getTranslationArray("project_invite_user");


//checks to see if the user is an group member
if(empty($_REQUEST['frm_codeProjeto'])) {
  Header("Location: $_CMAPP[services_url]/projects/projects.php?frm_amerror=project_code_does_not_exists");
}

$proj = new AMProject;
$proj->codeProject = $_REQUEST['frm_codeProjeto'];
try {
  $proj->load();
} catch(CMDBNoRecord $e) {
  Header("Location: $_CMAPP[services_url]/projects/projects.php?frm_amerror=project_code_does_not_exists");
}

$group = $proj->getGroup();
$isMember = $group->isMember($_SESSION['user']->codeUser);

if(!$isMember) {
   CMHTMLPage::redirect("$_CMAPP[services_url]/projects/project.php?frm_codProjeto=$proj->code&frm_amerror=not_group_member");
}


$pag = new AMTProjeto;
//adds an Javascript that checks for if thereis some checkbox checked before submit.
$pag->requires("inviteusers.js");
$pag->requires("search.js");

$title_box = $_language['invite_users_to_project'].' '.$proj->title;
$box = new AMTCadBox($title_box,
		     AMTCadBox::CADBOX_SEARCH,
		     AMTCadBox::PROJECT_THEME);

//adds the default message for the javascript display when you try to submit
//a empty form.
$pag->addScript("msg_check_some_user='$_language[error_user_not_select]';");

if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = "";
}

if(!isset($_REQUEST['frm_search_text'])){
	$_REQUEST['frm_search_text'] = "";
}

switch($_REQUEST['action']) {
 case "A_invite":
   if(empty($_REQUEST['frm_usersInvite'])) {
     CMHTMLPage::redirect("Location: $_CMAPP[services_url]/projects/inviteusers.php?frm_amerror=_user_not_select");
   }

   try {
     foreach($_REQUEST['frm_usersInvite'] as $user) {
       $group->userInvitationJoin($user,"");
     }
     $pag->addMessage($_language['msg_invitation_success']);
   } catch(CMDBException $e) {
     $pag->addError($_language['error_invitation_failed']);
   }

   if(empty($_REQUEST['frm_search_text'])) break;

 default:
   $_avaiable = $_SESSION['user']->listFriends();

   //put the curren group of the project into an associative
   //array so we can check if some user is in the group.
   if(empty($_SESSION['projects'][$proj->codeProject]['members'])) {
     $list = $group->listActiveMembers();
     foreach($list as $item) {
       $temp[$item->codeUser] = $item;
     }
     $_SESSION['projects'][$proj->codeProject]['members'] = $temp;
   };
   break;
 case "A_search":
   $temp = $_SESSION['environment']->searchUsers($_REQUEST['frm_search_text']);
   $_avaiable = $temp[0];
   break;   

}



//erase users from the container that are alredy project members
$men = "";
if(!empty($_avaiable) && $_avaiable->__hasItems()) {
  $temp = $_SESSION['projects'][$proj->codeProject]['members'];
  foreach($_avaiable->items as $key=>$item) {
    if(isset($temp[$item->codeUser])) {
      unset($_avaiable->items[$key]);
      $men = $_language["msg_users_removed"];
    }
  }
  if(!empty($men) && ($_REQUEST['action']=="A_search")) $pag->addMessage($men);
}
	       



//Start an form. I don't user AMWSmartform beacuse this form is
//very unusual

//start table - left
$box->add("<table border=0 cellpadding=0 cellspacing=0><tr>");

$box->add("<tr><td>");
$box->add("<form name=\"search\" action=\"$_SERVER[PHP_SELF]\" onSubmit=\"return Search_validateForm(this.elements['frm_search_text'].value))\">");
$box->add("<input type=hidden name=action value=\"A_search\">");
$box->add("<input type=hidden name=frm_codeProjeto value=\"$proj->codeProject\">");

$box->add('<span class="texto">'.$_language['search_users'].'</span> &nbsp;<input type=text name=frm_search_text value="'.$_REQUEST['frm_search_text'].'"> &nbsp;');

$box->add(AMMain::getSearchButton());
$box->add("</form>");

$box->add("</td></tr>");


$box->add("<td width=240 valign=top><br>");

$box->add('<form name="group" action="'.$_SERVER['PHP_SELF'].'" onSubmit="return checkUsers(this,\'frm_usersInvite[]\');">');
$box->add('<input type=hidden name=frm_search_text value="'.$_REQUEST['frm_search_text'].'"> ');
$box->add("<input type=hidden name=action value=\"A_invite\">");
$box->add("<input type=hidden name=frm_codeProjeto value=\"$proj->codeProject\">");

//print the list of team
$box->add("<table border=0 cellspacing=0  cellspacing=0><tr>");
if(!empty($_avaiable) && $_avaiable->__hasItems()) {
  foreach($_avaiable as $item) {
    $box->add('<tr>');
    $box->add('<td><input type=checkbox name="frm_usersInvite[]" value="'.$item->codeUser.'">');

    $box->add('<td>');
    //user picture
    $thumb = new AMUserThumb;
    $f = $item->picture;
    If(empty($f)) $f = AMUserFoto::DEFAULT_IMAGE;
    $thumb->codeFile = $f;
    try {
      $thumb->load();
    } catch(CMDBNoRecord $e) { }
    
    $box->add($thumb->getView());

    $box->add('<td>');
    $box->add(new AMTUserInfo($item));
    $box->add("</tr></td>");
  }
}
$box->add("</table>");

$box->add("</td>");

//end table
$box->add("</tr></table>");

//cancel and submit buttons
$cancel_url = $_CMAPP['services_url']."/projects/project.php?frm_codProjeto=".$proj->codeProject;

$box->add("<p align=center><input type=button onclick=\"window.location='$cancel_url'\" value=\"$_language[cancel]\">");
$box->add("&nbsp; <input type=submit  value=\"$_language[frm_invite]\">");
$box->add("</form>");



//finish the form

$pag->add($box);
echo $pag;