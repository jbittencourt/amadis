<?php

include "../../config.inc.php"; 

include($_CMAPP['path']."/templates/amtfotolibrary.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("library");

$page = new AMTLibrary("shared");
if(!isset($_REQUEST['frm_type'])){
	$_REQUEST['frm_type'] = "";
}

switch($_REQUEST['frm_type']) {
 case "project":
   
   $libprojz = new AMProjectLibraryEntry($_REQUEST["frm_codeProjeto"]);
   $libprojz->libraryExist();
   $proj = new AMProject;
   $proj->codeProject = $_REQUEST["frm_codeProjeto"];
   $proj->load();
   $box = new AMBProjLibraryShare($proj, 0, 0);
   break;

 default:
   $a = new AMUserLibraryEntry($_REQUEST["frm_codeUser"]);   
   $u = new AMUser;
   $u->codeUser = $_REQUEST["frm_codeUser"];
   try{
     $u->load();
   }catch(AMException $e){}
   $box = new AMBUserLibrary($u, 0, 0);
   break;
}

$page->add($box);

echo $page;