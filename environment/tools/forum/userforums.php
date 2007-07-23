<?php
include("../../config.inc.php");

$_language = $_CMAPP["i18n"]->getTranslationArray("projects");

$pag = new AMTProjeto;

if(array_key_exists("frm_codeUser", $_REQUEST)) {
  $user = new AMUser;
  $user->codeUser = $_REQUEST[frm_codeUser];

  try{
    $user->load();
  }catch(CMDBNoRecord $e){
    $location  = $_CMAPP[services_url]."/webfolio/userinfo.php?frm_amerror=user_not_exists";
    header("Location:$location");
  }
  
}
else {
  $user = $_SESSION["user"];
} 


$pag->add("<br /><br />");
$forums = $user->listForums();

$pag->add(new AMBMyForums($user->name, $forums));


echo $pag;



?>