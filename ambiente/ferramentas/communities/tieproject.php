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


$_language = $_CMAPP[i18n]->getTranslationArray("community_tie_project");

//checks to see if the user is an group member
if(empty($_REQUEST[frm_codeCommunity])) {
  Header("Location: $_CMAPP[services_url]/communities/communities.php?frm_amerror=community_code_does_not_exists");
}

$co = new AMCommunities;
$co->code = $_REQUEST[frm_codeCommunity];
try {
  $co->load();
} catch(CMDBNoRecord $e) {
  Header("Location: $_CMAPP[services_url]/communities/communities.php?frm_amerror=community_code_does_not_exists");
}

$group = $co->getGroup();
$isMember = $group->isMember($_SESSION[user]->codeUser);

if(!$isMember) {
   CMHTMLPage::redirect("$_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$co->code&frm_amerror=not_group_member");
}

$pag = new AMTCommunities;

//adds an Javascript that checks for if thereis some checkbox checked before submit.
$pag->requires("tieproject.js");
$pag->requires("search.js");

$title_box = $_language[tie_projects_to_community].' '.$co->name;
$box = new AMTCadBox($title_box,
		     AMTCadBox::CADBOX_SEARCH,
		     AMTCadBox::COMMUNITY_THEME);

//adds the default message for the javascript display when you try to submit
//a empty form.
$pag->addScript("msg_check_some_user='$_language[error_project_not_select]';");

switch($_REQUEST[action]) {
 case "A_tie":
   if(empty($_REQUEST[frm_projectTie])) {
     CMHTMLPage::redirect("Location: $_CMAPP[services_url]/communities/tieproject.php?frm_amerror=project_not_select");
   }
   try {
     foreach($_REQUEST[frm_projectTie] as $proj) {
       $rel = new AMCommunityProjects;
       $rel->codeProject = $proj;
       $rel->codeCommunity = $co->code;
       try{
	 $rel->save();
	 $pag->addMessage($_language[msg_tie_success]);
       }catch(CMObjEDuplicatedEntry $e) {
	 $pag->addError($_language[error_project_already_tied]);
       }
     }
   } catch(CMDBException $e) {
     $pag->addError($_language[error_tie_failed]);
   }

   if(empty($_REQUEST[frm_search_text])) 
     break;

 case "A_search":
   $temp = $_SESSION[environment]->searchProjects($_REQUEST[frm_search_text]);
   $_avaiable = $temp[0];
   break;   
 default:
   $_SESSION[communities][$co->code][projects] = $co->listProjects();
   $_avaiable = new CMContainer;
   break;
}
//erase projects from the container that are alredy members
$men = "";
if($_avaiable instanceof CMObj){
  if($_avaiable->__hasItems()) {        
    $temp = $_SESSION[communities][$co->code][projects];
    foreach($_avaiable->items as $key=>$item) {
      if(isset($temp[$item->codeProject])) {
	unset($_avaiable->items[$key]);
      }
    }
  }
}
	       
//Start an form. I don't use AMWSmartform beacuse this form is
//very unusual

//start table - left
$box->add("<table border=0 cellpadding=0 cellspacing=0><tr>");
$box->add("<tr><td>");
$box->add("<form name=\"search\" action=\"$_SERVER[PHP_SELF]\" onSubmit=\"return Search_validateForm(this.elements['frm_search_text'].value))\">");
$box->add("<input type=hidden name=action value=\"A_search\">");
$box->add("<input type=hidden name=frm_codeCommunity value=\"$co->code\">");

$box->add('<span class="texto">'.$_language[search_projects].'</span> &nbsp;<input type=text name=frm_search_text value="'.$_REQUEST[frm_search_text].'"> &nbsp;');

$box->add(AMMain::getSearchButton());
$box->add("</form>");

$box->add("</td></tr>");
$box->add("<td width=240 valign=top><br>");
$box->add('<form name="group" action="'.$_SERVER[PHP_SELF].'" onSubmit="return checkUsers(this,\'frm_projectTie[]\');">');
$box->add('<input type=hidden name=frm_search_text value="'.$_REQUEST[frm_search_text].'"> ');
$box->add("<input type=hidden name=action value=\"A_tie\">");
$box->add("<input type=hidden name=frm_codeCommunity value=\"$co->code\">");

//print the list of team
$box->add("<table border=0 cellspacing=0  cellspacing=0><tr>");

if($_avaiable->__hasItems()) {
  foreach($_avaiable as $item) {    
    $box->add('<tr>');
    $box->add('<td><input type=checkbox name="frm_projectTie[]" value="'.$item->codeProject.'">');
    $box->add('<td>');
    //proj picture
    $thumb = AMCommunityImage::getThumb($item->image);
    $box->add($thumb->getView());
    $box->add("</td><td align=center> $item->title");
    $box->add("</td></tr>");
  }
}

$box->add("</table>");

$box->add("</td>");

//end table
$box->add("</tr></table>");

//cancel and submit buttons
$cancel_url = $_CMAPP[services_url]."/communities/community.php?frm_codeCommunity=".$co->code;

$box->add("<p align=center><input type=button onclick=\"window.location='$cancel_url'\" value=\"$_language[cancel]\">");
$box->add("&nbsp; <input type=submit  value=\"$_language[frm_tie]\">");
$box->add("</form>");



//finish the form

$pag->add($box);
echo $pag;


?>