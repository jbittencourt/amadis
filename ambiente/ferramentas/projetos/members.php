<?
$_CMAPP[notrestricted] = 1;

include("../../config.inc.php");

include($_CMAPP[path]."/templates/amtprojeto.inc.php");
include($_CMAPP[path]."/templates/amsimplebox.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("project_members");

$pag = new AMTProjeto;

if(!empty($_REQUEST[frm_codProjeto])) {
  $proj = new AMProject;
  $proj->codeProject = $_REQUEST[frm_codProjeto];
  try{
    $proj->load();
  }catch(CMDBNoRecord $e){
    $location  = $_CMAPP[services_url]."/projetos/projeto.php?frm_amerror=project_not_exists";
    $location .= "&frm_codProjeto=".$_REQUEST[frm_codProjeto];
    header("Location:$location");
  }
} else { 
  $_REQUEST[frm_amerror] = "any_project_id";
  
  $pag->add("<br><div align=center><a href=\"".$_SERVER[HTTP_REFERER]."\" ");
  $pag->add("class=\"cinza\">".$_language[voltar]."</a></div><br>");
  echo $pag;
  die();
}


$group = $proj->getGroup();
$active_members = $group->listActiveMembers();
$retired_members = $group->listRetiredMembers();

$pag->add("<br><span class=\"project_title\">$_language[project]: ".$proj->title."<br></span>");
$pag->add("<a  href=\"".$_CMAPP[services_url]."/projetos/projeto.php?frm_codProjeto=".$proj->codeProject."\" class=\"green\">$_language[back_to_project]</a>");

$pag->add("<br><br>");

$box = new AMUserList($active_members,$_language[project_group_actual],AMUserList::PROJECT);

$box_old = new AMUserList($retired_members,$_language[project_group_old],AMUserList::PROJECT);

$pag->add($box);
$pag->add("<br>");
$pag->add($box_old);


echo $pag;


?>