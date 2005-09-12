<?

include_once("../../config.inc.php");

$curso = new AMCurso();
$curso->codCurso = $_REQUEST[frm_codCurso];
try{
  $curso->load();
}
catch(CMDBNoRecord $e){
  echo " erro , Curso nao encontrada" ;
}
$matriculas = $curso->listaMatriculas();
$pag = new AMTCurso();  
$pag->openNavMenu();
$pag->add ("<br>");

$tab = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
//tab->setTitle($lang[inscritos]);
//$tab->setRowTitle(array($lang[usuario],$lang[escola],$lang[cargo]));
//$tab->setClass("fontgray");
//$tab->setWidth(0,"60%");
//$tab->setWidth(1,"20%");
//$tab->setWidth(2,"20%");

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