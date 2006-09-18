<?
/**
 * This page is used to invite users to a community.
 *
 * The objective of this page is the allow community admin to
 * invite new users to his community. The invitation is stored
 * in AMCommunityMemberInvitation objects, and must be confirmed
 * by the user before they become part of the community. The system
 * check if the users is alredy part of the community, and in this
 * case don't show it.
 *
 * @author Cristiano S Basso <csbasso@lec.ufrgs.br>
 * @todo Don't display a users alredy invited in the list.
 **/

include("../../config.inc.php");


$_language = $_CMAPP[i18n]->getTranslationArray('communities','community_invite_user');


//checks to see if the user is an group member
if(empty($_REQUEST[frm_codeCommunity])) {
  Header("Location: $_CMAPP[services_url]/communities/communities.php?frm_amerror=community_code_does_not_exists");
}

$community = new AMCommunities;
$community->code = (integer) $_REQUEST[frm_codeCommunity];
try {
  $community->load();
  $group = $community->getGroup();
  $aco = $community->getACO();

} catch(CMDBNoRecord $e) {
  Header("Location: $_CMAPP[services_url]/communities/community.php?frm_amerror=community_code_does_not_exists");
}

$isMember = $group->isMember($_SESSION[user]->codeUser);
if(!$isMember) {
   CMHTMLPage::redirect("$_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$community->code&frm_amerror=not_group_member");
}


$pag = new AMTCommunities;

$admin = $aco->testUserPrivilege($_SESSION['user']->codeUser,
				 AMCommunities::PRIV_ADMIN);
$add_users = $aco->testUserPrivilege($_SESSION['user']->codeUser,
				     AMCommunities::PRIV_ADD_USERS);
if(!$admin && !$add_users) {
  //the current user doesn't have privileges
  $pag->addError($_language['error_no_privileges']);
  $url = $_CMAPP['services_url'].'/communities/community.php?frm_codeCommunity='.$community->code;
  $pag->add("<br><div align=center><a href='$url' class='cinza'>");
  $pag->add($_language['back']."</a></div><br>");
  echo $pag;

  die();  
}

//adds an Javascript that checks for if thereis some checkbox checked before submit.
$pag->requires("inviteusers.js");
$pag->requires("search.js");

$title_box = $_language[invite_users_to_community].' '.$community->name;
$box = new AMTCadBox($title_box,
		     AMTCadBox::CADBOX_SEARCH,
		     AMTCadBox::COMMUNITY_THEME);

//adds the default message for the javascript display when you try to submit
//a empty form.
$pag->addScript("msg_check_some_user='$_language[error_user_not_select]';");

//show the name of the current community and the back link.
$pag->add("<br><span class=\"titcomunidade\">$_language[community]: ".$community->name."<br></span>");
$pag->add("<a  href=\"".$_CMAPP[services_url]."/communities/community.php?frm_codeCommunity=".$community->code."\" class=\"green\">$_language[back_to_community]</a>");
$pag->add("<br><br>");


switch($_REQUEST[action]) {
 case "A_invite":
   if(empty($_REQUEST[frm_usersInvite])) {
     CMHTMLPage::redirect("Location: $_CMAPP[services_url]/communities/inviteusers.php?frm_amerror=_user_not_select");
   }

   try {
     foreach($_REQUEST[frm_usersInvite] as $user) {
       $group->userInvitationJoin($user,"");
     }
     $pag->addMessage($_language[msg_invitation_success]);
     CMHTMLPage::redirect("Location: $_CMAPP[services_url]/communities/inviteusers.php?frm_ammsg=msg_invitation_success");
   } catch(CMDBException $e) {
     $pag->addError($_language[error_invitation_failed]);
   }

   $_avaiable = $_SESSION[user]->listFriends();

   if(empty($_REQUEST[frm_search_text])) 
     break;
     
 case "A_search":
   $temp = $_SESSION[environment]->searchUsers($_REQUEST[frm_search_text]);
   $_avaiable = $temp[0];
   break;   
 default:
   $_avaiable = $_SESSION[user]->listFriends();

   //put the curren group of the project into an associative
   //array so we can check if some user is in the group.
   if(empty($_SESSION[communities][$community->code][members])) {
     $list = $group->listActiveMembers();
     foreach($list as $item) {
       $temp[$item->codeUser] = $item;
     }
     $_SESSION[communities][$community->code][members] = $temp;
   };
   break;
}



//erase users from the container that are alredy community members
$men = "";
if($_avaiable->__hasItems()) {
  $temp = $_SESSION[communities][$community->code][members];
  foreach($_avaiable->items as $key=>$item) {
    if(isset($temp[$item->codeUser])) {
      unset($_avaiable->items[$key]);
      $men = $_language["msg_users_removed"];
    }
  }
  if(!empty($men) && ($_REQUEST[action]=="A_search")) $pag->addMessage($men);
}
	       



//Start an form. I don't user AMWSmartform beacuse this form is
//very unusual

//start table - left
$box->add("<table border=0 cellpadding=0 cellspacing=0><tr>");

$box->add("<tr><td>");
$box->add("<form name=\"search\" action=\"$_SERVER[PHP_SELF]\" onSubmit=\"return Search_validateForm(this.elements['frm_search_text'].value))\">");
$box->add("<input type=hidden name=action value=\"A_search\">");
$box->add("<input type=hidden name=frm_codeCommunity value=\"$community->code\">");

$box->add('<span class="texto">'.$_language[search_users].'</span> &nbsp;<input type=text name=frm_search_text value="'.$_REQUEST[frm_search_text].'"> &nbsp;');

$box->add(AMMain::getSearchButton());
$box->add("</form>");

$box->add("</td></tr>");


$box->add("<td width=240 valign=top><br>");

$box->add('<form name="group" action="'.$_SERVER[PHP_SELF].'" onSubmit="return checkUsers(this,\'frm_usersInvite[]\');">');
$box->add('<input type=hidden name=frm_search_text value="'.$_REQUEST[frm_search_text].'"> ');
$box->add("<input type=hidden name=action value=\"A_invite\">");
$box->add("<input type=hidden name=frm_codeCommunity value=\"$community->code\">");

//print the list of team
$box->add("<table border=0 cellspacing=0  cellspacing=0><tr>");
if($_avaiable->__hasItems()) {
  foreach($_avaiable as $item) {
    $box->add('<tr>');
    $box->add('<td><input type=checkbox name="frm_usersInvite[]" value="'.$item->codeUser.'">');

    $box->add('<td>');
    //user picture
    $thumb = new AMUserThumb;
    $thumb->codeArquivo = ($item->foto==0 ? 1 : $item->foto);
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
$cancel_url = $_CMAPP[services_url]."/communities/community.php?frm_codeCommunity=".$community->code;

$box->add("<p align=center><input type=button onclick=\"window.location='$cancel_url'\" value=\"$_language[cancel]\">");
$box->add("&nbsp; <input type=submit  value=\"$_language[frm_invite]\">");
$box->add("</form>");



//finish the form

$pag->add($box);
echo $pag;


?>
