<?

include("../../config.inc.php");


$_language = $_CMAPP['i18n']->getTranslationArray("avisos");

$pag = new CMHTMLPage();

$pag->requires("avisos.css",CMHTMLOBJ::MEDIA_CSS);

$pag->setTitle($_language['warning_amadis']);
if(isset($_REQUEST['frm_codeAviso']) && empty($_REQUEST['frm_codeAviso'])) {
  die("quando aplicar o template fazer um erro decente");
}

$aviso = new AMAviso;
$aviso->codeAviso = $_REQUEST['frm_codeAviso'];

try {
  $aviso->load();
}
catch(CMDBNoRecord $e) {
  die($_language['error_aviso_not_found']);
}

$pag->add("<table cellpadding='0' cellspacing='0' border='0' width='100%'><tr>");
$pag->add("<td background='".$_CMAPP['media_url']."/images/img_avisos_bg.gif'><img src='".$_CMAPP['media_url']."/images/img_avisos.gif'></td></tr>");
$pag->add("<tr><td>");
$pag->add("<div class='avisos' id='tit'>$aviso->titulo</div><br />");
$pag->add("<div class='avisos' id='corpo'>$aviso->descricao</div>");
$pag->add("</td></tr></table>");
echo $pag;


?>