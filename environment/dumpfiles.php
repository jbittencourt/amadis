<?php
include('config.inc.php');

$q = new CMQuery('AMProject');
$q->setLimit(0, 10);
$result = $q->execute();
echo $result;
//echo utf8_encode($result->__toString());
die();
if($_REQUEST['dump']) {

	$dump = new DumpFiles;
	
}else die('te liga mano!!');
?>