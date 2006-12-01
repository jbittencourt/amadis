<?

$_CMAPP['notrestricted'] = 1;

include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray('communities');

$community = new AMCommunities;
$community->code = $_REQUEST[frm_codeCommunity];
try {
  $community->load();
  $group = $community->getGroup();
  $aco = $community->getACO();
} catch(CMDBNoRecord $e) {
  Header("Location: $_CMAPP[services_url]/communities/communities.php?frm_amerror=community_code_does_not_exists");
}


$pag = new AMTCommunities;

$pag->add("<a  href=\"".$_CMAPP[services_url]."/communities/community.php?frm_codeCommunity=".$community->code."\" class=\"green\">$_language[back_to_community]</a>");
$pag->add("<br><br>");


$projects = $community->listProjects();

$title = "$_language[list_projects_community] ".$community->name;
$box = new AMProjectList($projects,$title, AMTCadBox::CADBOX_LIST);
$pag->add($box);
echo $pag;

?>