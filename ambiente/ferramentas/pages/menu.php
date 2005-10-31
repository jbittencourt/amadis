<?
$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");

$pag = new AMHTMLPage;

$pag->add("<table cellpadding='0' cellspacing='0' width='100%' bgcolor='#8DA4C3'>");
$pag->add("<tr>");
$pag->add("<td width='147'><img src='$_CMAPP[images_url]/wf_logo_amadis.png' width='147' height='26' border='0'></td>");
$pag->add("<td align='center' valign='bottom'>");

$pag->add(new AMMainMenu);

$pag->add("</tr>");
$pag->add("</table>");

echo $pag;

?>