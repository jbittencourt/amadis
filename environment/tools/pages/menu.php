<?
/**
 * Visualization pages menu
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMUpload
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMHTMLPage,AMViewPageMenu
 */

$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");

$pag = new AMHTMLPage;

$pag->add("<table cellpadding='0' cellspacing='0' width='100%' bgcolor='#92A3AE'>");
$pag->add("<tr>");
$pag->add("<td width='147'><img src='$_CMAPP[images_url]/wf_logo_amadis.png' border='0'></td>");
$pag->add("<td valign='bottom'>");

$pag->add(new AMViewPageMenu);
$pag->add("</tr>");

$pag->add("</table>");

echo $pag;