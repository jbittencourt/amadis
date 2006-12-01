<?php

include_once("../../config.inc.php");
include_once("$pathuserlib/amadmin.inc.php");
include_once("$pathuserlib/amtipocursos.inc.php");
include_once("$pathtemplates/amtadmin.inc.php");
include_once("$pathtemplates/ambox.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");

$ui = new RDui("admin");
$lang = $_SESSION[environment]->getLangUi($ui);

$pag = new AMTAdmin();
$box = new AMBox();

//soh imprime se o usuario for super
if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {

  //cria o sub-menu
  if (empty($_REQUEST[frm_codTipoCurso]) or (!empty($_REQUEST[acao]))) {
    $itens[$lang[voltar_admin]] = "admin.php";
    $itens[$lang[criar_tipo_curso]] = "config_oficinas.php?acao=A_criar_tipo_curso";
  }
  
  else $itens[$lang[voltar_config_oficina]] = "config_oficinas.php";
  
  $pag->setSubMenu($itens);
  $pag->add ("<br>");
  


  switch($_REQUEST[acao]){

  case "A_make_tipo_curso":

    $tipoCurso = new AMTipoCursos();
    $tipoCurso->loadDataFromRequest();
    $tipoCurso->salva();
    header("Location:$_SERVER[PHP_SELF]");

    break;

  case "A_apagar_tipo_curso":

    $tipoCurso = new AMTipoCursos($_REQUEST[frm_codTipoCurso]);
    $tipoCurso->deleta();
    header("Location:$_SERVER[PHP_SELF]");

    break;

  case "A_criar_tipo_curso":

    $form = new WSmartForm("AMTipoCursos","form1",$_SERVER[PHP_SELF],array("codTipoCurso"));

    $form->setCancelUrl("config_oficinas.php");
    $form->setDesign(WFORMEL_DESIGN_OVER);
    $form->setLabelClass("fontgray");
    $form->addComponent("acao", new WHidden("acao","A_make_tipo_curso"));

    $box->add($form);
    $pag->add($box);
    $pag->imprime();

    break;

  case "A_editar_tipo_curso":

    $tipoCurso = new AMTipoCursos($_REQUEST[frm_codTipoCurso]);
    $form = new WSmartForm("AMTipoCursos","form1",$_SERVER[PHP_SELF],'',array("codTipoCurso"));

    $form->setCancelUrl("config_oficinas.php");
    $form->setDesign(WFORMEL_DESIGN_OVER);
    $form->setLabelClass("fontgray");

    $form->loadDataFromObject($tipoCurso);
    
    $box->add($form);
  
    //cria o form pra apagar

    $js = "function apaga() {";
    $js .= "if (confirm('$lang[confirma_apagar_tipo_curso]')) document.formApaga.submit();}";

    $pag->addScript($js);

    $apaga = "<form method=post name=\"formApaga\" action=\"$_SERVER[PHP_SELF]\">";
    $apaga .= "<input type=hidden name=\"acao\" value=\"A_apagar_tipo_curso\">";
    $apaga .= "<input type=hidden name=\"frm_codTipoCurso\" value=\"";
    $apaga .= "$_REQUEST[frm_codTipoCurso]\">";
    $apaga .= "<table width=\"100%\"><tr><td width=\"350\"><center>";
    $apaga .= "<input type=button onClick=\"apaga();\" value=\"$lang[apagar_tipo_curso]\">";
    $apaga .= "</center></td><td>&nbsp;</td></tr></table>";
    $apaga .= "</form>";
    
    $box->add($apaga);
    $pag->add($box);
    $pag->imprime();
    
    break;
  
  default:

    $box->setTitle($lang[menu_tipo_cursos]);
  
    $tiposCursos = $_SESSION[environment]->listaTipoCursos();
    if(!empty($tiposCursos->records)){
      foreach($tiposCursos->records as $item){
	$link  = "<a href=$_SERVER[PHP_SELF]?acao=A_editar_tipo_curso";
	$link .= "&frm_codTipoCurso=$item->codTipoCurso>$item->nomTipoCurso</a>";
	$box->addItem($link);
      }
    }
    $pag->add($box);
    $pag->imprime();

    break;
  }

}else {
  $pag->add("<center><font color=red>$lang[acesso_nao_permitido]</font></center>");
  $pag->imprime();
}

?>
