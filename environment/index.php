<?php
$_CMAPP['notrestricted']=true;
include("config.inc.php");
include($_CMAPP['path']."/templates/aminicial.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("inicial");

$pag = new AMInicial();

/**
 * BEM-VINDO AO AMADIS
 **/
$pag->add('<div class="presentation">');
$pag->add('<img src="'.$_CMAPP['images_url'].'/logo_lec.gif" style="float:right; margin: 15px;" alt=""/>');
$pag->add($_language['lec_text_1'].'</div>');

if($_conf->app->interface->applets=='true') {
	$pag->add('<div><APPLET  code="br.ufrgs.lec.amadis.applets.ProjectPulse.ProjectsPulse" archive="'.$_CMAPP['url'].'/media/applets/ProjectsPulse.jar" width="540" height="90">');
	$pag->add('<PARAM name="amadisurl" VALUE="'.$_CMAPP['url'].'">');
	$pag->add('</APPLET></div>');
}

if(empty($_SESSION['user'])) {
  /**
   * QUERO ME CADASTRAR
   **/
  $pag->add('<div style="clear:right;">');
  $pag->add('<a href="'.$_CMAPP['services_url'].'/webfolio/register.php">');
  $pag->add('<img src="'.$_CMAPP['imlang_url'].'/img_cadastrar_amadis.gif" alt="" /></a></div>');
} else $pag->add('<br style="clear:right;" />');

//best visited projects
$projs = $_SESSION['environment']->listTopProjects();
$box1 = new AMColorBox("$_CMAPP[imlang_url]/box_projvisitados_tit.gif",AMColorBox::COLOR_BOX_PURPLE);
$box1->add(new AMTProjectsSmallList($projs));
$box1->add("<a href='$_CMAPP[services_url]/projects/listprojects.php'>&raquo; $_language[list_all_projects]</a>");

//new projects
$projs = $_SESSION['environment']->listNewProjects();
$box2 = new AMColorBox("$_CMAPP[imlang_url]/box_projnovos_tit.gif",AMColorBox::COLOR_BOX_DARKPURPLE);
if($projs->__hasItems()){
  $box2->add(new AMTProjectsSmallList($projs));
  $box2->add("<a href='$_CMAPP[services_url]/projects/listprojects.php'>&raquo; $_language[list_all_projects]</a>");
}
else{ 
  $box2->add($_language['no_project_found']); 
}

//new communities
$comms = $_SESSION['environment']->listNewComminities();
$box3 = new AMColorBox("$_CMAPP[imlang_url]/box_comunovas_tit.gif",AMColorBox::COLOR_BOX_YELLOW);
if($comms->__hasItems()){ 
  $box3->add(new AMTCommunitySmallList($comms));
  $box3->add("<a href='$_CMAPP[services_url]/communities/listcommunities.php'>&raquo; $_language[list_all_communities]</a>");
}
else{ $box3->add($_language['no_community_found']); }
$cols = new AMTwoColsLayout;

$cols->add($box1,AMTwoColsLayout::LEFT);
//projects by area
$cols->add(new AMBProjectsArea(true),AMTwoColsLayout::LEFT);

$cols->add($box2,AMTwoColsLayout::RIGHT);
$cols->add($box3,AMTwoColsLayout::RIGHT);

$cols->add(new AMBStatistics, AMTwoColsLayout::RIGHT);


$pag->add($cols);



echo $pag;