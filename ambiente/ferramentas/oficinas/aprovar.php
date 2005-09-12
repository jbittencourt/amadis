<?
include_once("../../config.inc.php");

function load() {
  global $listar, $curso;

  switch($listar) {
  case "todas":
    $matriculas = $curso->listaMatriculas();
    break;
  case "negadas":
    $matriculas = $curso->listaMatriculas("negadas");
    break;
  default:
    $matriculas = $curso->listaMatriculas("naojulgadas");
  }
  return ($matriculas);
}

$listar = $_REQUEST[listar];
$curso = new AMCurso();
$curso->codCurso = $_REQUEST[frm_codCurso];
try{
  $curso->load();
}
catch(CMDBNoRecord $e){
  echo "Nao existe o curso"; die();
}

$coordenador = $curso->eCoordenador($_SESSION[user]->codeUser);

if (!$coordenador){
  $pag = new AMTCurso();
  $pag->addScript("window.alert('Voce nao eh coordenador');location.href='cursos.php'");
  echo $pag;
} 


$matriculas = load();

$pag = new AMTCurso();
$pag->openNavMenu();  
$pag->add ("<br>");

$itens = array();
switch ($listar) {
 case "todas":
   $itens["ver_matriculas_nao_julgadas"] = "aprovar.php?frm_codCurso=".$_REQUEST[frm_codCurso];
   $itens["ver_matriculas_negadas"] = "aprovar.php?listar=negadas&frm_codCurso=".$_REQUEST[frm_codCurso];
   $box = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
   $box->add("Todas Matriculas<br>");
   $box->add("<a href=$itens[ver_matriculas_nao_julgadas]> Matriculas nao julgadas </a><br>");
   $box->add("<a href=$itens[ver_matriculas_negadas]> Matriculas negadas </a>");
   $box->add("<br>");
   $pag->add($box);
   break;
 case "negadas":
   $itens["ver_matriculas_nao_julgadas"] = "aprovar.php?frm_codCurso=".$_REQUEST[frm_codCurso];
   $itens["ver_todas_matriculas"] = "aprovar.php?listar=todas&frm_codCurso=".$_REQUEST[frm_codCurso];
   unset($box);
   $box = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
   $box->add("Matriculas Negadas<br>");
   $box->add("<a href=$itens[ver_matriculas_nao_julgadas]> Matriculas nao julgadas</a><br>");
   $box->add("<a href=$itens[ver_todas_matriculas]> Todas Matriculas  </a>");
   $box->add("<br>");
   $pag->add($box);
   break;
 default:
   $itens["ver_todas_matriculas"] = "aprovar.php?listar=todas&frm_codCurso=".$_REQUEST[frm_codCurso];
   $itens["ver_matriculas_negadas"] = "aprovar.php?listar=negadas&frm_codCurso=".$_REQUEST[frm_codCurso];
   unset($box);
   $box = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
   $box->add("Matriculas esperando avaliacao<br>");
   $box->add("<a href=$itens[ver_matriculas_negadas]> Matriculas nao julgadas</a><br>");
   $box->add("<a href=$itens[ver_todas_matriculas]> Todas Matriculas  </a>");
   $box->add("<br>");
   $pag->add($box);
}

$itens["voltar"] = "curso.php?frm_codCurso=".$curso->codCurso;



if ($_REQUEST[acao] == "A_aprovar") {
  $mat = new AMCursoParticipante();
  $mat->codUser = $_REQUEST[user];
  $mat->codeCurso = $curso->codCurso;
  try{
    $mat->load();
  }
  catch(CMDBNoRecord $e){
    echo "bizarro";
  }
  $mat->flagAutorizado=1;
  $mat->matriculado=1;
  try{
    $mat->save();
  }
  catch(CMDBQueryError $e){
    echo " catou erro" ;
  }

  

  // $noticia = new AMNoticia();
//   $noticia->codUser = $mat->codUser;
//   $noticia->flaLida = 0;
//   $noticia->tempo = time();
//   $noticia->desNoticia = $lang[noticia_aprovacao].$curso->nomCurso;
//   $noticia->salva();
  unset ($matriculas);
  $matriculas = load();
  
  $pag->add ("<center><font color=red>Inscricao Aprovada<br><br></center></font>");

}

if ($_REQUEST[acao] == "rejeitar") {
  $mat = new AMCursoParticipante();
  $mat->codUser = $_REQUEST[user];
  $mat->codeCurso = $curso->codCurso;
  try{
    $mat->load();
  }
  catch(CMDBNoRecord $e){
    echo "bizarro";
  }
  $mat->flagAutorizado = 2;
  $mat->save();
  
//   $noticia = new AMNoticia();
//   $noticia->codUser = $mat->codUser;
//   $noticia->flaLida = 0;
//   $noticia->tempo = time();
//   $noticia->desNoticia = $lang[noticia_reprovacao].$curso->nomCurso;
//   $noticia->salva();
  unset ($matriculas);
  $matriculas = load();
  
  $pag->add ("<center><font color=red>Inscricao no curso rejeitada<br><br></center></font>");
}

$tab = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
// tab->setTitle($lang[matriculas]);
// $tab->setRowTitle(array($lang[usuario],$lang[escola],$lang[cargo],$lang[aprovado]));
// $tab->setClass("fontgray");
// $tab->setWidth(0,"40%");
// $tab->setWidth(1,"15%");
// $tab->setWidth(2,"15%");
//$tab->size("70%");


if ($matriculas->items!=0) {
  foreach ($matriculas as $matricula) {
    
    $user = new AMUser();
    $user->codeUser = $matricula->codUser;
    try{
      $user->load();
    }
    catch(CMDBNoRecord $e){
      echo " usuario nao existe" ;
    }

    $aprovado = "<form method=post action=\"aprovar.php\" name=\"altera".$user->codeUser."\">";
    $aprovado .= "<input type=hidden name=\"frm_codCurso\" value=\"$_REQUEST[frm_codCurso]\">";
    $aprovado .= "<input type=hidden name=\"user\" value=\"".$user->codeUser."\">";
    $aprovado .= "<select name=\"acao\" onChange=\"document.altera".$user->codeUser.".submit();\">";
    
   if ($matricula->flagAutorizado==1 AND $matricula->matriculado==0) {
     $aprovado .= "<option value=\"nada\">aguardando]</option>";
     $aprovado .= "<option value=\"A_aprovar\">aprovado]</option>";
     $aprovado .= "<option value=\"rejeitar\">rejeitado]</option>";
     
   }
      
   if ($matricula->flagAutorizado==1 AND $$matricula->matriculado==1){
     $aprovado .= "<option value=\"A_aprovar\">aprovado]</option>";
     $aprovado .= "<option value=\"rejeitar\">rejeitado]</option>";
   }
      
   if ($matricula->flagAutorizado==2){
      $aprovado .= "<option value=\"rejeitar\">rejeitado]</option>";
      $aprovado .= "<option value=\"A_aprovar\">aprovado]</option>";
      break;
   }

    $aprovado .= "</select></form>";
//     $escola = new AMEscola($user->codEscola);
    
//     unset ($cargo);
//     if (!empty($user->desCargo)) $cargo = $user->desCargo;
//     else $cargo = "&nbsp;";
    $tab->add($aprovado);
    $tab->add($user->name."&nbsp;".$user->name.")&nbsp;-&nbsp;<i>".date("d/n/Y h:i", $matricula->tempo)."</i>");
  }
}


$pag->add ($tab);
$pag->add ("<BR>");
echo $pag;
?>