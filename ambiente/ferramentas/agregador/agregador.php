<?
$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");
include('lastRSS.php');

$_language = $_CMAPP['i18n']->getTranslationArray("projects");
//$_language = $_CMAPP['i18n']->getTranslationArray("diary");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

$pag = new AMTAgregador();

//checks if the user is a member of the project
if(isset($_REQUEST['frm_codProjeto']) && !empty($_REQUEST['frm_codProjeto'])) {
  $proj = new AMProjeto;
  $proj->codeProject = $_REQUEST['frm_codProjeto'];
  try{
    $proj->load();
    $group = $proj->getGroup();
  }catch(CMDBNoRecord $e){
    $location  = $_CMAPP[services_url]."/projetos/projeto.php?frm_amerror=project_not_exists";
    $location .= "&frm_codProjeto=".$_REQUEST[frm_codProjeto];
    header("Location:$location");
  }
} else {
  $_REQUEST['frm_amerror'] = "any_project_id";

  $pag->add("<br><div align=center><a href=\"".$_SERVER['HTTP_REFERER']."\" ");
  $pag->add("class=\"cinza\">".$_language['back']."</a></div><br>");
  echo $pag;
  die();
}

$isMember = false;
if(!empty($_SESSION['user'])) {
  $isMember = $group->isMember($_SESSION['user']->codeUser);
  if(!$isMember) $proj->hit();
}

if(isset($_REQUEST['frm_codProjeto']) && !empty($_REQUEST['frm_codProjeto'])) {
  $proj = new AMProjeto;
  $proj->codeProject = $_REQUEST['frm_codProjeto'];
  try{
    $proj->load();
    $group = $proj->getGroup();
  }catch(CMDBNoRecord $e){
    $location  = $_CMAPP[services_url]."/projetos/projeto.php?frm_amerror=project_not_exists";
    $location .= "&frm_codProjeto=".$_REQUEST[frm_codProjeto];
    header("Location:$location");
  }
} else {
  $_REQUEST['frm_amerror'] = "any_project_id";

  $pag->add("<br><div align=center><a href=\"".$_SERVER['HTTP_REFERER']."\" ");
  $pag->add("class=\"cinza\">".$_language['back']."</a></div><br>");
  echo $pag;
  die();
}

$pag->add("<font class=\"project_title\">$_language[project]: ".$proj->title."</font><br/><br/>");
$pag->add("<table><tr><td>");
$pag->add(new AMTProjectImage($proj->image));
$pag->add("</td>");
if ($isMember) {
  $pag->add("<td><span style=\"padding-left: 30px;\">");
  $pag->add("<a href=\"$urledit\" class =\"green\">&raquo; Editar lista de blogs</a></span></td>");
}
$pag->add("</tr></table><br/>");



// load some RSS file
$rss = new lastRSS; 

$q = new CMQuery('AMProjectBlogs');
$q->setFilter("AMProjectBlogs::codeProject=".$_REQUEST['frm_codProjeto']);
$blogs=$q->execute();

foreach($blogs as $blog) {

  if ($rs = $rss->get($blog->address)) {
    $caixa = new AMBoxAgregador($rs,$userDiario->codeUser,"",0);
    $pag->add($caixa);
  } else {
    $pag->add('Error: RSS file "'.$blog->address.'" not found...');
  }

}
/*
if ($rs = $rss->get('http://gnomedesktop.org/node/feed')) {
  //if ($rs = $rss->get('http://www.freshfolder.com/rss.php')) {
//  if ($rs = $rss->get('http://lothlorien.lec.ufrgs.br/~dmbasso/ferramentas/diario/diarioRSS.php?frm_codeUser=103')) {
  $caixa = new AMBoxAgregador($rs,$userDiario->codeUser,"xawaskaaaaaa",0);
  $pag->add($caixa);
} else {
  $pag->add('Error: RSS file not found...');
}

// http://lothlorien.lec.ufrgs.br/~dmbasso/ferramentas/diario/diarioRSS.php?frm_codeUser=95
if ($rs = $rss->get('http://lothlorien.lec.ufrgs.br/~dmbasso/ferramentas/diario/diarioRSS.php?frm_codeUser=103')) {
  $caixa = new AMBoxAgregador($rs,$userDiario->codeUser,"xawaskaaaaaa",0);
  $pag->add($caixa);
} else {
  $pag->add('Error: RSS file not found...');
}

*/
echo $pag; 

?>
