<?
$_CMAPP[notrestricted] = 1;
include("../../config.inc.php");

echo '<frameset rows="35,*" border="0">';
echo '<frame id="ammenu" src="'.$_CMAPP[services_url].'/pages/menu.php">';
echo "<frame id='amcontent' src='$_CMAPP[pages_url]/$_REQUEST[frm_page]'>";
echo '</frameset>';


?>