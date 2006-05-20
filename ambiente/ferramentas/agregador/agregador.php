<?
/**
 * Visualization of the RSS Feeds to projects
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMAgregator
 * @version 1.0
 * @author Daniel M. Basso <daniel@basso.inf.br>
 */

$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");
include('lastRSS.php');

$_language = $_CMAPP['i18n']->getTranslationArray("projects");

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
  $pag->add("<a href=\"$urledit\" class =\"green\">$_language[edit_blogs_list]</a></span><br>");
}


// load some RSS file
$rss = new lastRSS; 

$q = new CMQuery('AMProjectBlogs');
$q->setFilter("AMProjectBlogs::codeProject=".$_REQUEST['frm_codProjeto']);
$blogs=$q->execute();

if($blogs->__hasItems()) {
  $link = "if(this.value!=0) ";
  $link .= "location.href='$_CMAPP[services_url]/agregador/agregador.php?frm_codProjeto=$_REQUEST[frm_codProjeto]&frm_codeBlog='+this.value";

  $pag->add("&nbsp;&nbsp;<select name='blogs' onChange=\"$link\">");
  $pag->add("<option value=0>$_language[select_one]</option>");
  
  foreach($blogs as $blog) {
    $pag->setRSSFeed($blog->address, $blog->title);
    if($blog->codeBlog == $_REQUEST[frm_codeBlog])
      $b = $blog;
    $pag->add("<option value='$blog->codeBlog'>$blog->title</option>");
  }
  
  $pag->add("</select>");
}

$pag->add("</tr></table><br/>");

if(!isset($b)) {
  $k = array_keys($blogs->items);
  $b = $blogs->items[$k[0]];
}
if(!empty($blog)) {
  if ($rs = $rss->get($b->address)) {
    $caixa = new AMBoxAgregador($rs,$b->address,"",0);
    $pag->add($caixa);
  } else {
    $pag->add('Error: RSS file "'.$b->address.'" not found...');
  }
}


echo $pag; 

?>
