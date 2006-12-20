<?
$_CMAPP['notrestricted'] = True;

include("../../config.inc.php");
$_language = $_CMAPP['i18n']->getTranslationArray("webfolio");

$pag = new CMHTMLObj;

$pag->requires("lib.js", CMHTMLObj::MEDIA_JS);
$user = new AMUser;
$user->codeUser = (isset($_REQUEST['frm_codeUser'])? $_REQUEST['frm_codeUser'] : '');
$user->load();


$where_to_go = "$_CMAPP[services_url]/webfolio/userinfo_details.php?frm_codeUser=$_REQUEST[frm_codeUser]";

$pag->add("<div class=\"tooltip\">");
$pag->add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=100%>");
$pag->add("<tr> ");
$pag->add("<td align=\"right\" width=16><img src=\"$_CMAPP[images_url]/tooltip_box_es.png\" width=\"16\" height=\"14\" border=\"0\"></td>");
$pag->add("<td background=\"$_CMAPP[images_url]/tooltip_box_bg_superior.png\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
$pag->add("<td width=16><img src=\"$_CMAPP[images_url]/tooltip_box_ds.png\" width=\"16\" height=\"14\" border=\"0\"></td>");
$pag->add("</tr>");
$pag->add("<tr> ");
$pag->add("<td align=\"right\" background=\"$_CMAPP[images_url]/tooltip_box_bg_lateral.gif\"><img src=\"imagens/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
$pag->add("<td valign=\"top\" id=\"tooltip_internal_area\">");

//picture of the user

$pag->add("<a href=\"$where_to_go\">");
$pag->add(new AMTUserImage($user->picture));
$pag->add("</a>");

$pag->add("<div id=\"perfil\"> ");
$pag->add("<!-- box perfil -->");
$pag->add("<font class=\"texto\"><b>$user->username</b>");
$pag->add("<br>".$user->email);
$pag->add("<br>");

$pag->add("<div class=\"tooltip_line\"><a href=\"$where_to_go\">$_language[webfolio_visit]</a></div>");

$pag->add("<!-- fim box perfil -->");
if($user->codeUser != $_SESSION['user']->codeUser) {
  
  $status = $_SESSION['environment']->checkIsOnLine($user->codeUser);
  $onclick = "onClick=\"window.Finder_openChatWindow('$_CMAPP[services_url]/finder/finder_chat.php', '$user->codeUser');\"";
  $pag->add("<div class=\"tooltip_line\">");
  switch($status) {
  
  case AMFinder::FINDER_NORMAL_MODE :
    $pag->add("<img id='UserIco_$user->codeUser' $onclick src=\"$_CMAPP[images_url]/ico_user_on_line.png\">");
    $pag->add("<a $onclick class=\"ini_conversa cursor\">$_language[init_chat]</a>");
    break;
  
  case AMFinder::FINDER_BUSY_MODE :
    $pag->add("<img id='UserIco_$user->codeUser' $onclick src=\"$_CMAPP[images_url]/ico_user_ocupado.png\">");
    $pag->add("<a $onclick class=\"ini_conversa cursor\">$_language[init_chat]</a>");
    break;
    
  case AMFinder::FINDER_HIDDEN_MODE :
    $pag->add("<img id='UserIco_$user->codeUser' $onclick src=\"$_CMAPP[images_url]/ico_user_ocupado.png\">");
    $pag->add("<a $onclick class=\"ini_conversa cursor\">$_language[init_chat]</a>");
    break;
  default:
    $pag->add("<img id='UserIco_$user->codeUser' src=\"$_CMAPP[images_url]/ico_user_off_line.png\">");
    $pag->add("<a class=\"ini_conversa cursor\">$_language[off_line_user]</a>");
    break;
    
  }
  $pag->add("</div>");
}

$pag->add("</td>");
$pag->add("<td background=\"$_CMAPP[images_url]/tooltip_box_bg_lateral2.gif\"><img src=\"imagens/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
$pag->add("</tr>");
$pag->add("<tr> ");
$pag->add("<td align=\"right\"><img src=\"$_CMAPP[images_url]/tooltip_box_ei.png\" width=\"16\" height=\"16\" border=\"0\"></td>");
$pag->add("<td background=\"$_CMAPP[images_url]/tooltip_box_bg_inferior.png\"></td>");
$pag->add("<td><img src=\"$_CMAPP[images_url]/tooltip_box_di.png\" width=\"16\" height=\"16\" border=\"0\"></td>");
$pag->add("</tr>");
$pag->add("</table>"); 
$pag->add("<!-- fim tooltip -->");
$pag->add("</div>");

echo $pag;

die();

?>