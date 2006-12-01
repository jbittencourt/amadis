<?php

include_once("../../config.inc.php");

function inscrito($curso) {
  global $_SESSION, $lang;

 
 $matricula = new AMCursoParticipante();
 $matricula->codeCurso = $curso->codCurso;
 $matricula->codUser = $_SESSION[user]->codeUser;
 try{
   $matricula->load();
 }
 catch(CMDBNorecord $e){

 }
 
 
  if($matricula->flagCoordenador =="1")
    $status = "coordenador";
  else{
    if(($matricula->matriculado=="1") && ($matricula->flagAutorizado=="1"))
      $status = "inscrito"; 
    else 
      $status = "nao_inscrito";
  }


  switch($status){

  case "inscrito":
    $linkInscr  = "<a href=\"curso.php?frm_codCurso=".$curso->codCurso."\" ";
    $linkInscr .= "class=\"fontgray\">$curso->nome</a>";
    return $linkInscr;
    break;
	  
  case "nao_inscrito":
    $linkInscr  = "<a href=\"inscr_curso.php?frm_codCurso=".$curso->codCurso."\" ";
    $linkInscr .= "class=\"fontgray\">$curso->nome</a>";
    return $linkInscr;
    break;

  case "coordenador":
    $linkInscr  = "<a href=\"curso.php?frm_codCurso=".$curso->codCurso."\" ";
    $linkInscr .= "class=\"fontgray\">$curso->nome</a>";
    return $linkInscr;
    break;
  }


}

$pag = new AMTCurso();
$pag->openNavMenu();  
$pag->add ("<br>");


switch ($_REQUEST[acao]) {
  
 default:

   //Caixa Com o link de criar cursos
   $BcriaCurso = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
   $BcriaCurso->add("<a href=cad_curso.php>Crie seu Curso</a><br>");
   $BcriaCurso->add("<a href=cursos.php?acao=encerradas> Veja os Cursos Finalizados</a><br>");
  
   //note($_SESSION);die();
   //MEUS CURSOS E CURSOS QUE COORDENO
   $BmeusCursos = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
   $BmeusCursos->add("Meus Cursos<br>");
   
   
   $meusCursos = $_SESSION[user]->listaCursos();
   if(!empty($meusCursos->items)){
     foreach($meusCursos as $curso){
       $BmeusCursos->add("<a href=curso.php?frm_codCurso=".$curso->codCurso." > ".$curso->nome." </a><br>");
     }
   }
   
   //CURSOS COM INSCRICOES ABERTAS
   $BcursosAbertos = new AMColorBox("",AMColorBox::COLOR_BOX_BLUA);
   $BcursosAbertos->add("Cursos com Inscricoes Abertas<br>");
   $cursosInscrAbertas = $_SESSION[environment]->listaCursos("InscrAbertas");
   if(!empty($cursosInscrAbertas->items)){
     foreach($cursosInscrAbertas as $curso){      
       $link = inscrito($curso);

       $BcursosAbertos->add($link."<br>");
      
     }
   }
   
   //CURSOS COM INSCRICOES ENCERRADAS E EM ANDAMENTO
   $BcursosEmAndamento = new AMColorBox("",AMColorBox::COLOR_BOX_BEGE);
   $BcursosEmAndamento->add("Curso com Inscricoes Encerradas ou em Andamento<br>");
   $cursoInscrEncerradas = $_SESSION[environment]->listaCursos("InscrEncerradas");
   
   if(!empty($cursoInscrEncerradas->items)){
     foreach($cursoInscrEncerradas as $curso){
       $link = inscrito($curso);
       $BcursosEmAndamento->add($link."<br>");
        
     }
   }
   $pag->add($BcriaCurso);
   $pag->add("<br>");
   $pag->add($BmeusCursos);
   $pag->add("<br>");
   $pag->add($BcursosAbertos);
   $pag->add("<br>");
   $pag->add($BcursosEmAndamento);
     
   break;

 case "encerradas":
   $BmostraCurso = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
   $BmostraCurso->add("<a href=cad_curso.php>Crie seu Curso</a><br>");
   $BmostraCurso->add("<a href=cursos.php> Veja os cursos abertos</a><br>");



   $cursos = $_SESSION[environment]->listaCursos("Encerrados");

   $BcursosEncerrados = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
   $BcursosEncerrados->add("Cursos Encerrados<br>");
   
   if (!empty($cursos->items)){
     foreach ($cursos as $curso)  {
       $link = inscrito($curso);
       $BcursosEncerrados->add($link);
     }
   }
   $pag->add($BmostraCurso);
   $pag->add("<br>");
   $pag->add($BcursosEncerrados);
   

}

   $pag->add ("<br>");
echo $pag; 

?>
