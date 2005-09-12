<?

include("../../config.inc.php");

$_SESSION[amadis][forum][visualization] = $_REQUEST[frm_status];
echo "Status:$_REQUEST[frm_status]";

?>