<?php
include('../../config.inc.php');

$q = new CMQuery('AMCommunities');
$res = $q->execute();

foreach($res as $item) {
    $aco = new CMACO($item);
    $aco->description = "COMMUNITY ".$item->name;
	$aco->time = time();
	try {
		$aco->save();
	} catch(CMDBException $e) {
		Throw new AMException("An error ocurred creating the community group.");
	}
	
	$item->codeACO = (integer) $aco->code;
	//$aco->addUserPrivilege((integer) $_SESSION['user']->codeUser, self::PRIV_ADMIN);
	$item->state = CMObj::STATE_DIRTY;
	$item->save();
}
noteLastquery();
?>