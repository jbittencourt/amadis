<?php
/** 
 * This file do a visualization of the webpages hosted in AMADIS.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMUpload
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */
$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("upload");

echo "<html><head><title>$_language[amadis]</title></head>";
echo "<frameset rows='22,*' border='0'>";
echo "<frame id='ammenu' src='$_CMAPP[services_url]/pages/menu.php?$_SERVER[QUERY_STRING]'>";

$dir = "$_CMAPP[path]/environment/pages/$_REQUEST[frm_page]";
if(file_exists("$dir/index.html") || file_exists("$dir/index.htm") || file_exists("$dir/index.php")) {
  echo "<frame id='amcontent' src='$_CMAPP[pages_url]/$_REQUEST[frm_page]'>";  
} else {
  if(isset($_REQUEST['frm_codeUser']) and !empty($_REQUEST['frm_codeUser'])) {
    echo "<frame id='amcontent' src='$_CMAPP[services_url]/pages/list.php?frm_page=$_REQUEST[frm_page]&frm_codeUser=$_REQUEST[frm_codeUser]'>";
  } else {
    echo "<frame id='amcontent' src='$_CMAPP[services_url]/pages/list.php?frm_page=$_REQUEST[frm_page]&frm_codeProject=$_REQUEST[frm_codeProject]'>";
  }
}
echo "</frameset>";
echo "</html>";