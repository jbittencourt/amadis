<?php

include_once("../../config.inc.php");


$curso = new AMCurso();

$curso->codCurso = $_REQUEST[frm_codCurso];
try{
  $curso->load();
}
catch(CMDBNoRecord $e){
  echo "Curso nao existe $e";
}

$pag = new AMTCurso();  
$pag->openNavMenu();
$pag->add("<br>");





if ($_REQUEST[acao] == "inscrever") {

    $inscr = new AMCursoParticipante();
    $inscr->codUser = $_SESSION[user]->codeUser;
    $inscr->codeCurso= $curso->codCurso;
    try{
      $inscr->load();
    }
    catch(CMDBNoRecord $e){
      echo " erro impossivel" ;
    }
    


      $inscr->tempo = time();
      
    if ($curso->flagInscricaoAutomatica=="1") {
      $inscr->flagAutorizado ="1";
      $inscr->matriculado ="1";
      try{
	$inscr->save();
      }
      catch(CMDBQueryError $e){
	echo "nao foi possivel salvar";
      }
      $pag->add("<p align=center class=fontgray>Inscricao Aceita<br><br><a class=fontcolor href=\"editarcurso.php?frm_codCurso=".$curso->codCurso."\">ir para o curso</a></p>");
    }
    else {
      $inscr->flagAutorizado =1;
      try{
	$inscr->save();
      }
      catch(CMDBQueryError $e){
	echo "nao foi possivel salvar";
      }
    $pag->add("<p align=center class=fontgray>Solicitao Inscricao Enviada<br><br><a class=fontcolor href=\"cursos.php\">voltar</a></p>");
    
    }
    echo $pag;
    die();
}

$pag->add("<table width=\"100%\"><tr><td width=\"90%\">");

$tab = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
$tab->add($curso->nome);

$tab->add("<p class=\"fontgray\">");
$tab->add("&raquo; Insricao Inicio: ".date("j/n/Y",$curso->datInscricaoInicio)."<br>");
$tab->add("&raquo; Inscricao fim: ".date("j/n/Y",$curso->datInscricaoFim)."<br><br>");

$tab->add("&raquo; Data Inicio Curso: ".date("j/n/Y",$curso->datInicio)."<br>");
$tab->add("&raquo; Data fim curso: ".date("j/n/Y",$curso->datFim)."<br><br></p>");
//$linhas = new AMDotLine();
//$tab->add($linha);

$tab->add("<p class=\"fontgray\">".$curso->descricao."</p>");
$tab->add("<font size=1><br></font>");

//$tab->add(new AMDotLine(100%));

if($curso->datInscricaoInicio > time()) {
  $tab->add("<p align=center class=fontcolor>Inscricoes apartir de &nbsp;".date("j/n/Y", $curso->datInscricaoInicio)."</p>");
}
elseif ($curso->datInscricaoFim < time()) {
  $tab->add("<p align=center class=fontcolor>Inscricoes terminadas&nbsp;".date("j/n/Y", $curso->datInscricaoFim)."</p>");
}
else {

  if($reload!=true){ 
    
    $req = new AMCursoParticipante();
    $req->codUser= $_SESSION[user]->codeUser;
    $req->codeCurso = $curso->codCurso;
    try{
      $req->load();
    }
    catch(CMDBNoRecord $e) {
      $req = new AMCursoParticipante();
      $req->codUser= $_SESSION[user]->codeUser;
      $req->codeCurso = $curso->codCurso;
      $req->tempo = time();
      $req->flagAutorizado=0;
      
      try{
	$req->save();
      }
      catch(CMDBQueryError $e){
	$pag->add("Usuario jah solicitou inscricao");
      }
    }
    $reload=true;
  }

  // matriculado = 0 se nao estiver inscrito  ( flagAutorizado
  //             1 se jah foi requisitada
  //             2 se jah foi rejeitada antes a inscricao 
  
  

  
  if ($req->flagAutorizado==0) {
    $tab->add ("<p align=center class=fontcolor><a class=fontcolor href=\"inscr_curso.php?acao=inscrever&frm_codCurso=".$curso->codCurso."\">Inscrever</a></p>");
  }
  if ($req->flagAutorizado==2) {
    $tab->add ("<p align=center class=fontcolor>Inscricao jah rejeitada&nbsp;".date("j/n/Y",$matricula->tempo)." hshs $lang[inscr_jah_rejeitada2]</p>");
  }

  if ($req->flagAutorizado=="1") {
    $tab->add("<p align=center class=fontcolor>Inscricao Solicitada &nbsp;</p>");   
  }
}

$pag->add($tab);
$pag->add("</td><td width=\"10%\">&nbsp;</td></tr></table>");
$pag->add ("<br>");
echo $pag; 
die();

?>
