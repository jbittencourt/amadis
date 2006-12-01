<?

$_CMAPP[notrestricted] = 1;

include("../../config.inc.php");


$_language = $_CMAPP[i18n]->getTranslationArray("communities");

$pag = new AMTCommunities;


if(!empty($_REQUEST[frm_codeCommunity])) {
  $community = new AMCommunities;  
  $community->code = $_REQUEST[frm_codeCommunity];
  try{
    $community->load();
    $group = $community->getGroup();
  }catch(CMDBNoRecord $e){
    $_REQUEST[frm_amerror] = "community_not_exists";
    
    echo $pag;
    die();
  }
} else { 
  $_REQUEST[frm_amerror] = "no_community_id";
  
  $pag->add("<br><div align=center><a href=\"".$_SERVER[HTTP_REFERER]."\" ");
  $pag->add("class=\"cinza\">".$_language[voltar]."</a></div><br>");
  echo $pag;
  die();
}


$group = $community->getGroup();
$active_members = $group->listActiveMembers();
$retired_members = $group->listRetiredMembers();

$pag->add("<br><span class=\"titcomunidade\">$_language[community]: ".$community->name."<br></span>");
$pag->add("<a  href=\"".$_CMAPP[services_url]."/communities/community.php?frm_codeCommunity=".$community->code."\" class=\"green\">$_language[back_to_community]</a>");
$pag->add("<br><br>");

$box = new AMUserList($active_members,$_language[project_group_actual],AMUserList::COMMUNITY);

$box_old = new AMUserList($retired_members,$_language[project_group_old],AMUserList::COMMUNITY);

$pag->add($box);
$pag->add("<br>");
$pag->add($box_old);


echo $pag;

?>