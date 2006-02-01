<?
$_CMAPP['notrestricted']=1;
include("config.inc.php");
include($_CMAPP['path']."/templates/aminicial.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("inicial");

$pag = new AMInicial();



/**
 * BEM-VINDO AO AMADIS
 **/
$pag->add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">");
$pag->add("<tr><td colspan=\"3\"><img src=\"".$_CMAPP['images_url']."/dot.gif\" width=\"20\" height=\"20\"></td></tr>");
$pag->add("<tr><TD colspan=\"3\" class=\"textoint\"><img src=\"".$_CMAPP['images_url']."/logo_lec.gif\" hspace=\"60\" vspace=\"20\" border=\"0\" align=\"right\">$_language[lec_text_1] <br><b>");

$pag->add("</tr>");


$pag->add("<APPLET  code=\"br.ufrgs.lec.amadis.applets.ProjectPulse.ProjectsPulse\" archive=\"$_CMAPP[url]/media/applets/ProjectsPulse.jar\" width=540 height=90>");
$pag->add("<PARAM name=amadisurl VALUE=\"$_CMAPP[url]\">");
$pag->add("</APPLET>");

if(empty($_SESSION['user'])) {
  /**
   * QUERO ME CADASTRAR
   **/
  $pag->add("<tr><td colspan=\"3\">");
  $pag->add("<a href=\"$_CMAPP[services_url]/webfolio/register.php\">");
  $pag->add("<img src=\"".$_CMAPP['imlang_url']."/img_cadastrar_amadis.gif\" border=0></tr></a>");
}
//linha
$pag->add("<tr><td colspan=\"3\"><img src=\"".$_CMAPP['images_url']."/dot.gif\" width=\"20\" height=\"20\"></td></tr>");
$pag->add("<tr><td colspan=\"3\">");
$pag->add("</td></tr>");

$pag->add("</table>");

//best visited projects
$projs = $_SESSION['environment']->listTopProjects();
$box1 = new AMColorBox("$_CMAPP[imlang_url]/box_projvisitados_tit.gif",AMColorBox::COLOR_BOX_PURPLE);
$box1->add(new AMTProjectsSmallList($projs));
$box1->add("<a href='$_CMAPP[services_url]/projetos/listprojects.php'>&raquo; $_language[list_all_projects]</a>");

//new projects
$projs = $_SESSION['environment']->listNewProjects();
$box2 = new AMColorBox("$_CMAPP[imlang_url]/box_projnovos_tit.gif",AMColorBox::COLOR_BOX_DARKPURPLE);
$box2->add(new AMTProjectsSmallList($projs));
$box2->add("<a href='$_CMAPP[services_url]/projetos/listprojects.php'>&raquo; $_language[list_all_projects]</a>");


//new communities
$comms = $_SESSION['environment']->listNewComminities();
$box3 = new AMColorBox("$_CMAPP[imlang_url]/box_comunovas_tit.gif",AMColorBox::COLOR_BOX_YELLOW);
$box3->add(new AMTCommunitySmallList($comms));
$box3->add("<a href='$_CMAPP[services_url]/communities/listcommunities.inc.php'>&raquo; $_language[list_all_communities]</a>");


$cols = new AMTwoColsLayout;

$cols->add($box1,AMTwoColsLayout::LEFT);
//projects by area
$cols->add(new AMBProjectsArea(true),AMTwoColsLayout::LEFT);

$cols->add($box2,AMTwoColsLayout::RIGHT);
$cols->add($box3,AMTwoColsLayout::RIGHT);

$cols->add(new AMBStatistics, AMTwoColsLayout::RIGHT);


$pag->add($cols);



echo $pag;

?>