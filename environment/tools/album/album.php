<?php
include("../../config.inc.php");

$_CMAPP['notrestricted'] = 1;
$_language = $_CMAPP['i18n']->getTranslationArray("album");

$page = new AMTAlbum;

if(isset($_REQUEST['action'])) {
	$album = new AMAlbum;
	switch($_REQUEST['action']){
		case "save":						
			try{
				$album->saveEntry();
				header("Location:$_SERVER[PHP_SELF]?frm_ammsg=file_successful_sent");
			}catch(CMException $e){
				new AMErrorReport($e, 'AMBAlbum::doAction', AMLog::LOG_ALBUM);
				header("Location:$_SERVER[PHP_SELF]?frm_amerror=send_file");
			}						
			break;

		case "delete":
			try{
				$album->deleta($_REQUEST["id"]);
				header("Location:$_SERVER[PHP_SELF]?frm_ammsg=file_successful_delete");
			}catch(CMException $e){
				new AMErrorReport($e, 'AMBAlbum::doAction', AMLog::LOG_ALBUM);
				header("Location:$_SERVER[PHP_SELF]?frm_amerror=del_file");
			}
			break;

		case "edita_comment":
			try{
				$album->editComment($_REQUEST['photo'], $_REQUEST['comment_edited']);
				header("Location:$_SERVER[PHP_SELF]?frm_ammsg=comment_successfull_edited");
			}catch(CMException $e){
				new AMErrorReport($e, 'AMBAlbum::doAction', AMLog::LOG_ALBUM);
				header("Location:$_SERVER[PHP_SELF]?frm_amerror=edit_comment");
			}
			break;
	}
}

$album = new AMAlbum;
$album->codeUser = $_SESSION['user']->codeUser;

$box = new AMBAlbum($album->getMyPhotos(), $_language['userImages']."".$_SESSION['user']->name);
$page->add("<br />");
$page->add($box);

echo $page;