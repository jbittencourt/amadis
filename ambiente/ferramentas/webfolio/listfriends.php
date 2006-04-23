<?

$_CMAPP[notrestricted] = True;
include("../../config.inc.php");


$_language = $_CMAPP[i18n]->getTranslationArray("webfolio");


if(empty($_REQUEST[frm_codeUser])) {
  $user = $_SESSION[user];
} else {
  $user = new AMUser;
  $user->codeUser = $_REQUEST[frm_codeUser];

  try{
    $user->load();
  }catch(CMDBException $e) {
    $pag->addError($_language[user_not_loaded]);
    $pag->add("<a href=$_SERVER[HTTP_REFERER]>$_language[voltar]</a>");
    echo $pag;
    die();
  }
}



$pag = new AMTWebfolio;

$friends = $user->listFriends();
$box = new AMColorBox("$_CMAPP[imlang_url]/box_wfamigos_tit.gif",AMColorBox::COLOR_BOX_PINK);

if($friends->__hasItems()) {
  $box->add(new AMTIconList($friends,AMTIconList::USER_LIST,$friends->count()));
} 
else {
  $box->add("<span class='texto'>$_language[user_no_friends]</span>");
}


$pag->add($box);

echo $pag;


?>