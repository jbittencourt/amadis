<?
include_once("../../config.inc.php");

if(empty($_REQUEST['frm_codeUser'])) {
  die("Nao sei com quem conversar");
};


$_SESSION['finder']->startChat($_REQUEST['frm_codeUser']);

$time = $_SESSION['finder']->getTime($_REQUEST['frm_codeUser']);

$pag = new AMTFinderChat($_REQUEST['frm_codeUser']);
$pag->mainLoop($time-1);
$pag->stopChat();



?>