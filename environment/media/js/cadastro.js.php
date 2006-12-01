<?
$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");
$_language = $_CMAPP['i18n']->getTranslationArray("register");

?>


function passwd_validate(password, re_password) {
  

  if(password.value == re_password.value) {
    return true;
  }else {
    alert('<? echo $_language['error_password_dot_not_macth'] ?>');
    return false;
  }

}


function echeck(str) {

  var at="@";
  var dot=".";
  var lat=str.indexOf(at);
  var lstr=str.length;
  var ldot=str.indexOf(dot);
  if (str.indexOf(at)==-1) {
    return false;
  }

  if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr) {
    return false;
  }

  if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr) {
    return false;
  }

  if (str.indexOf(at,(lat+1))!=-1) {
    return false;
  }

  if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot) {
    return false;
  }

  if (str.indexOf(dot,(lat+2))==-1) {
    return false;
  }
		
  if (str.indexOf(" ")!=-1) {
    return false;
  }

  return true;
}



function email_validate(email){

  if(!echeck(email.value)) {
    alert('<? echo $_language['error_invalid_email'] ?>');
    return false;
  }

  return true;
}