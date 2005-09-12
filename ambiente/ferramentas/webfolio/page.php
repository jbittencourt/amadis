<?
$_CMAPP[notrestricted] = True;
include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("webfolio");

$pag = new AMTWebfolio();
$pag->closeNavMenu();
$pag->setLeftMargin(30);
$pag->add("<img src=$_CMAPP[images_url]/dot.gif width=20 height=20>");

$iframe_url = "$_CMAPP[pages_url]/users/user_";
if(!empty($_REQUEST[frm_codeUser])) $iframe_url .= $_REQUEST[frm_codeUser];
else $iframe_url .= $_SESSION[user]->codeUser;

$pag->add("<iframe onload=\"resizeFrame()\" id=\"id_if_pagina\" name=if_pagina src=\"$iframe_url\" width=\"800\" height=\"500\"></iframe>");

$js.= "var ns4=document.layers?1:0;";
$js.= "var ie4=document.all&&navigator.userAgent.indexOf(\"Opera\")==-1;";
$js.= "var ns6=document.getElementById&&!document.all?1:0;";
$js.= "function resizeFrame() {";
$js.= "   var width;";
$js.= "   var ifpagina;";
$js.= "   if(ns6) {";
$js.= "      ifpagina = document.getElementById(\"id_if_pagina\"); ";
$js.= "      width=window.innerWidth;\n";
$js.= "   } else {";
$js.= "      ifpagina = document.all.if_pagina;";
$js.= "      width = document.body.offsetWidth;";
$js.= "   };";
$js.= "   ifpagina.style.width = width - ".($pag->leftmargin+80).";";
$js.= "};";
$js.= "window.captureEvents(Event.RESIZE);window.onResize = resizeFrame;";

$pag->addScript($js);
    
echo $pag;


?>