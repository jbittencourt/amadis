<?php

// Pedro Pimentel - 22/02/2005 - zukunft@gmail.com


/* Lista os inscritos do respectivo curso, eh acessivel tanto pelo coordenador como pelos alunos do curso

recebe como parametro o numero do curso a ser mostrado  */

include_once("../../config.inc.php");

  if (empty($_REQUEST[frm_codCurso])){
    $pag = new AMTCurso();  
    $pag->addScript("window.alert('Voce nao Pode acessar sem codigo');location.href='../cursos/cursos.php'");
    echo $pag;
    die();
  }


$curso = new AMCurso();
$curso->codCurso = $_REQUEST[frm_codCurso];
try{
  $curso->load();
}
catch(CMDBNoRecord $e){

}

$matriculas = $curso->listaMatriculas();
$pag = new AMTCurso();  
$pag->openNavMenu();
$pag->add ("<br>");

$tab = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);

if ($matriculas) {
  foreach ($matriculas as $matricula) {
    $user = new AMUser();
    $user->codeUser = $matricula->codUser;
    try{
      $user->load();
    }
    catch(CMDBNoRecord $e){
      echo " nao ha usuario";
    }    
    $tab->add($user->name." AKA ".$user->username."<br>");
  }
}
else $tab->add("<i>Nenhum Inscrito</i>");

$pag->add ($tab);
echo $pag;

?>