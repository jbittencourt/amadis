<?
/**
 * This file has as its main pourpose to work as a warp to js or css files that are
 * not in a Internet acessible path. It is used to include files from the CM->Devel
 * framework, so the user dosen't need to copy then to their own application.
 **/

$_CMAPP[notrestricted] = 1;
include("../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("smartform");

switch($_REQUEST[type]) {
 case "css":
   header("Content-type: text/css");
   break;
 case "js":
   header("text/javascript");
   break;
}

$file = $_CMDEVEL[path]."/".$_REQUEST[frm_file];
// //check if the user is not trying to include an file outside the
// //cmdevel directory using ../../file filenames.
// if(substr($file,0,strlen($_CMDEVEL[path]))!=$_CMDEVEL[path]) {
//   die("Hummm, you cannot access a file outside CMDevel path!\n");
// }
include($file);

?>
