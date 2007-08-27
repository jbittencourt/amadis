<?php

$_CMAPP['notrestricted'] = 1;

include("../config.inc.php");


//copied from http://br.php.net/manual/en/function.imagecreatefromjpeg.php
//thanks a lot
function error() {
	$im  = imagecreate(100, 100); /* Create a blank image */
	$bgc = imagecolorallocate($im, 255, 255, 255);
	$tc  = imagecolorallocate($im, 0, 0, 0);
	imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
	/* Output an errmsg */
	imagestring($im, 1, 5, 5, "Error loading image.", $tc);

	header("Content-Type: image/png");
	imagepng($im);
	die();
}



if(empty($_REQUEST['method'])) {
	if($_REQUEST['debug']==1) {
		die("Render method not defined.");
	}
	else {
		error();
	}
}

$f = false;
if(isset($_REQUEST['frm_codeFile'])) $f = (integer) $_REQUEST['frm_codeFile'];
if(!$f && !isset($_REQUEST['frm_id'])) $_REQUEST['method'] = 'default';

switch($_REQUEST['method']) {
 case "db":
 	$imagem = new AMFile;
 	$imagem->codeFile = (integer) $_REQUEST['frm_codeFile'];
 	Try {
 		$imagem->load();
 	}catch(CMDBNoRecord $e) {

 		//tests if a debug var is set in the request, if not, send an error image, otherwise print the exception.
		new AMErrorReport($e, 'IMAGEWRAPPER::'.$_REQUEST['method'], AMLog::LOG_CORE );
		AMLog::commit();
		
		error();
 	}
 	break;
 case "session":
 	$imagem = unserialize($_SESSION['amadis']['imageview'][$_REQUEST['frm_id']]);
 	header("Content-Type: ".$imagem->mimeType);
	die($imagem->data);
 	break;
}

$path =  (string) $_conf->app[0]->paths[0]->files;
$filename = $path.'/'.$imagem->codeFile.'_'.$imagem->name;
if (file_exists($filename)) {
	header("Content-Type: ".$imagem->mimeType);

	$handle = fopen ($filename, "r");
	$img = fread ($handle, filesize ($filename));
	fclose ($handle);
	
	echo $img;
}
else {
	error();
}