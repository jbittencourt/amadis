<?
$_CMAPP[notrestricted] = 1;
include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("projects");

$pag = new AMTProjeto;

if(!empty($_REQUEST[frm_codeProject])) {
     $proj = new AMProjeto;
     $proj->codeProject = $_REQUEST[frm_codeProject];
     try{
       $proj->load();
     }catch(CMDBNoRecord $e){
       $location  = $_CMAPP[services_url]."/projetos/projeto.php?frm_amerror=project_not_exists";
       $location .= "&frm_codProjeto=".$_REQUEST[frm_codeProject];
       header("Location:$location");
     }
} else { 
  $_REQUEST[frm_amerror] = "any_project_id";
  $pag->add("<br><div align=center><a href=\"".$_SERVER[HTTP_REFERER]."\" ");
  $pag->add("class=\"cinza\">".$_language[voltar]."</a></div><br>");
  echo $pag;
  die();
}


if(!empty($_REQUEST[frm_action])) {
  switch($_REQUEST[frm_action]) {
  case "A_create":
    $_language = $_CMAPP[i18n]->getTranslationArray("forum");

    $form = new AMWSmartForm(AMForum, "cad_forum", $_SERVER[PHP_SELF],array('name'));
//     $form->addComponent("open", new CMWCheckbox("frm_open","1"));
    $form->addComponent("action", new CMWHidden("frm_action","A_make"));
    $form->addComponent("codeProject", new CMWHidden("frm_codeProject",$_REQUEST[frm_codeProject]));

    $pag->add($form);
    echo $pag;
    die();
    break;
  case "A_make":


    $forum = new AMForum;
    $forum->name = $_REQUEST[frm_name];
    $forum->creationTime = time();
    $forum->save();
    
    $link = new AMProjectForum;
    $link->codeForum = $forum->code;
    $link->codeProject = $_REQUEST[frm_codeProject];
    $link->save();

    $link = "$_CMAPP[services_url]/forum/forum.php?frm_codeForum=$forum->code";
    CMHtmlPage::redirect($link);
    die();

    break;
  }
}

$pag->add("<br><br>");
$forums = $proj->listForums();

$pag->add(new AMBForum($proj->title, $forums));


echo $pag;


?>