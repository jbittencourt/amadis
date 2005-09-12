<?

$_CMAPP[notrestricted] = True;
include("../../config.inc.php");


$_language = $_CMAPP[i18n]->getTranslationArray("scraps");


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

$messages = $user->listMyMessages();

$box = new AMTCadBox($_language[scraps_of].' '.$user->name,AMTCadBox::CADBOX_LIST,AMTCadBox::DEFAULT_THEME);

if($messages->__hasItems()) {
  $box->add('<table id="scraps">');
  foreach($messages as $men) {
    $box->add('<tr>');
    $box->add('<td>');
    
    $thumb = new AMUserThumb;
    $thumb->codeArquivo = $men->author[0]->foto;
    $thumb->load();
      
    $box->add($thumb->getView());

    
    $box->add('<td>');
    $box->add(new AMTUserInfo($men->author[0]));
    $box->add(':'.$men->message);
    $box->add('<td>');
    $box->add(date("$_language[hour_format]<br>$_language[date_format]",$men->time));
    
    if($_SESSION[environment]->logged) {
      if($men->codeTo==$_SESSION[user]->codeUser) {
	$box->add('<td>');
	$link = "$_CMAPP[services_url]/webfolio.php?codeUserMessage=$men->code&action=A_delete";
	if(!empty($_REQUEST[frm_codeUser])) {
	  $link.="&frm_codeUser=$_REQUEST[frm_codeUser]";
	}
	$box->add("<a href='$link'>$_language[delete]</a>");
      }
    } 
  }
  $box->add('</table>');
} else {
  $box->add('<span class="texto">'.$_language[no_scraps].'</span>');
}

$pag->add($box);

echo $pag;


?>