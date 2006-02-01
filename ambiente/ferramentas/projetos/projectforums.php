<?
$_CMAPP[notrestricted] = 1;
include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("projects");

$pag = new AMTProjeto;
$pag->requires("forum.css",CMHTMLObj::MEDIA_CSS);

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
    $title = "<div class=\"forum_project_title\">$_language[project_forum_create] $proj->title</div>";

    $_language = $_CMAPP[i18n]->getTranslationArray("forum");

    $box = new AMColorBox($title,AMColorBox::COLOR_BOX_BEGE);
    $box->add("<br/>");

    $box->add("<FORM ACTION='$_SERVER[PHP_SELF]'>");
    $box->add("<INPUT TYPE=hidden NAME=frm_codeProject value='$_REQUEST[frm_codeProject]'>");
    $box->add("<INPUT TYPE=hidden NAME=frm_action value='A_make'>");
    $box->add("$_language[frm_name] <INPUT TYPE=text NAME=frm_name VALUE='$_REQUEST[frm_name]'>");

    $aco = new CMACO();
    $acorender = new AMACORender($aco);
    $box->add($acorender);

    $box->add("</FORM>");

    $pag->add($box);
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

$title = "<div class=\"forum_project_title\">$_language[project_forum_title] $proj->title</div>";

$box = new AMColorBox($title,AMColorBox::COLOR_BOX_BEGE);
$box->add("<br/>");
$box->add(new AMBForum($proj->title, $forums));

$box->add("<br/>");

$box->add("<FORM ACTION='$_CMAPP[services_url]/projetos/projectforums.php' METHOD=post>");
$box->add("<INPUT TYPE=HIDDEN NAME=\"frm_codeProject\" VALUE=\"$_REQUEST[frm_codeProject]\">");
$box->add("<INPUT TYPE=HIDDEN NAME=\"frm_action\" VALUE=\"A_create\">");

$box->add("<BUTTON type=SUBMIT class=\"image-button forum-submit-button\">");
$box->add("<img src=\"$_CMAPP[imlang_url]/img_novo_forum2.gif\" >");
$box->add("</BUTTON>");

$box->add("</FORM>");

$pag->add($box);


echo $pag;


?>