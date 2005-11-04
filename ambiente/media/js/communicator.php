<?
$_CMAPP['notrestricted']=1;
include("../../config.inc.php");

$com = new AMCommunicator;

foreach($_SESSION['communicator'] as $item) {
  $com->addClassHandler($item);
}

$com->__initServer();

?>
