<?
include("../../config.inc.php");
$_language = $_CMAPP[i18n]->getTranslationArray("cursos");

$pag = new AMTcurso();

$pag->setLeftMargin(25);
$pag->add("<br>");

$curso = new AMCurso();
$curso->codCurso = $_REQUEST[frm_codCurso];
try{
  $curso->load();
}
catch(CMDBNoRecord $e){
  $pag->addScript("window.alert('Curso Solicitado nao existe');location.href='cursos.php'");
  echo $pag;
  die();
}

$coordenador = $curso->eCoordenador($_SESSION[user]->codeUser);
//$coordenador retorna 1 se for coordenador
//                     0 se for aluno


switch($_REQUEST[acao]) {
 case "A_upload":
   if ($coordenador) {
     //vai ter acesso as funcoes de coordenador
      $iframe_url = $_CMAPP[url]."/ferramentas/upload/upload.php?frm_upload_type=course&frm_codCurso=".$curso->codCurso;
   }
   break;
 default:
   $dir = @opendir($_CMAPP[path]."/ambiente/paginas/curso_".$curso->codCurso);
   if ($dir == true) {
     $file = readdir($dir);
     $file = readdir($dir);
     $file = readdir($dir);
     if ($file != false) {
       $iframe_url = $_CMAPP[url]."/ambiente/media/paginas/curso_".$curso->codCurso."/";
     }
     closedir($dir);
   }       
    
   if(empty($iframe_url)) 
     $iframe_url = $_CMAPP[url]."/media/paginas/vazio.html";
  
   break;
}


function criaLink($texto,$link){
  $link = "<a href=\"$link\"> $texto </a><br>";
  return $link;
}

$links[] = criaLink($_language[chat_curso],$_CMAPP[url]."/ferramentas/chat/chat.php?frm_codCurso=".$curso->codCurso);
$links[] = criaLink($_language[lista_inscritos_curso], "inscritos.php?frm_codCurso=".$curso->codCurso."");
//$links[] = criaLink($_language[diario_curso], $_CMAPP[url]."/ferramentas/diario/diario.php?frm_codCurso=".$curso->codCurso."");
if ($coordenador) {
  if ($curso->flagInscricaoAutomatica==0) {
    $links[] = criaLink($_language[aprovar_pedidos], "aprovar.php?frm_codCurso=".$curso->codCurso."");
  }
  $links[] = criaLink($_language[upload_curso],"curso.php?acao=A_upload&frm_codCurso=".$curso->codCurso."");
  $links[] = criaLink($_language[atualizar_curso] ,"atualizar.php?frm_codCurso=".$curso->codCurso."");
}


$pag->add("<iframe onload=\"resizeFrame()\" id=\"id_if_pagina\" name=if_pagina src=\"$iframe_url\" width=\"800\" height=\"500\"></iframe>");

$box = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
$box->setWidth("100%");



//adiciona o box com os links
foreach($links as $link){
  $box->add($link);
}
$pag->add($box);

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
die();


?>