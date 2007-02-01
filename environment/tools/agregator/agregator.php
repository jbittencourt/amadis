<?php
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
include($_LAST_RSS['path'] . '/lastRSS.php');

$_language = $_CMAPP['i18n']->getTranslationArray("projects");

$pag = new AMTAgregator();

//$pag->addNotification(AMMain::getChangePwButton(1));
//checks if the user is a member of the project
if(isset($_REQUEST['frm_codProjeto']) && !empty($_REQUEST['frm_codProjeto'])) {
  $proj = new AMProject;
  $proj->codeProject = $_REQUEST['frm_codProjeto'];
  try{
    $proj->load();
    $group = $proj->getGroup();
  }catch(CMDBNoRecord $e){
    $location  = $_CMAPP[services_url]."/projects/project.php?frm_amerror=project_not_exists";
    $location .= "&frm_codProjeto=".$_REQUEST['frm_codProjeto'];
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


// load some RSS file
$rss = new lastRSS;


$rss->cache_dir = CACHE_DIR;
$rss->cache_time = 3600; // one hour

$blogs = AMAgregatorFacade::getSources($_REQUEST['frm_codProjeto']);

if($blogs->__hasItems()) {
  $link = "if(this.value!=0) ";
  $link .= "location.href='$_CMAPP[services_url]/agregator/agregator.php?frm_codProjeto=$_REQUEST[frm_codProjeto]&frm_codeBlog='+this.value";

  //$pag->add("&nbsp;&nbsp;<select name='blogs' onChange=\"$link\">");
  //$pag->add("<option value=0>$_language[select_one]</option>");
  
  foreach($blogs as $blog) {
    $pag->setRSSFeed($blog->address, $blog->title);
    if($blog->codeBlog == $_REQUEST[frm_codeBlog])
      $b = $blog;
    //$pag->add("<option value='$blog->codeBlog'>$blog->title</option>");
  }
  //$pag->add("</select>");
}


if(!isset($b)) {
  $k = array_keys($blogs->items);
  $b = $blogs->items[$k[0]];
}
if(!empty($blog)) {
  if ($rs = $rss->get($b->address)) {
    $box = new AMBoxAgregador($rs,$b->address,$isMember);
    $box->setHeader($proj);
    $box->addFilter($b->agregator->items[0]->keywords);
    $pag->add($box);
  } else {
    $pag->add('Error: RSS file "'.$b->address.'" not found...');
  }
}


echo $pag; 