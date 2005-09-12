<?php

include_once("../../config.inc.php");
include_once("$pathuserlib/amadmin.inc.php");
include_once("$pathuserlib/amprojeto.inc.php");
include_once("$pathuserlib/amarea.inc.php");

include_once("$pathtemplates/amtadmin.inc.php");
include_once("$pathtemplates/ambox.inc.php");

include_once("$rdpath/smartform/wsmartform.inc.php");

$ui = new RDui("admin");
$lang = $_SESSION[ambiente]->getLangUi($ui);

$pag = new AMTAdmin();

//soh imprime se o usuario for super
if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {

  //cria o sub-menu
  if (empty($_REQUEST[frm_codArea]) or ($_REQUEST[acao] == "A_apaga") or ($_REQUEST[acao] == "A_salvar")) {
    $itens[$lang[voltar_admin]] = "admin.php";
    $itens[$lang[criar_area]] = "editar_areas.php?frm_codArea=new";
  }
  
  else $itens[$lang[selecionar_outra_area]] = "editar_areas.php";
  
  $pag->setSubMenu($itens);
  $pag->add ("<br>");
  

  //salva os dados postados do formulario
  if ($_REQUEST[acao] == "A_apaga") {
    $apaga = new AMArea($_REQUEST[frm_codArea]);
    $projs = $apaga->listaProjetos();

    //nao permite que se apague a area se tiver usuario cadastrado nela
    if (!empty($projs->records)) {
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[existem_projs_area]</font></font></center><br><br>");
    }

    else {
      $apaga->deleta();
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[area_apagada]</font></font></center><br><br>");
    }

    unset ($_REQUEST[frm_codArea]);
  }

  if($_REQUEST[acao] == "A_salvar") {

    $save = new AMArea($_REQUEST[frm_codArea]);

    //verifica se a area jah existe caso esteja sendo cadsatrada uma nova area
    if ($save->nomArea == "") {
      if ($_SESSION[ambiente]->existeArea($_REQUEST[frm_nomArea]) == "1") {
	$notSave = "1";
	$pag->add ("<br><font class=fontgray><font color=red size=+1><center>$lang[area_jah_existe]</font></font></center><br><br>");
      }
    }

    //seta os valores do objeto e salva
    if ($notSave != "1") {
      $save->nomArea = $_REQUEST[frm_nomArea];
      $save->codPai = $_REQUEST[frm_codPai];

      if ($_REQUEST[frm_codPai] != 0) {
	$pai = new AMArea($_REQUEST[frm_codPai]);
	$save->intGeracao = $pai->intGeracao + 1;
      }

      else
	$save->intGeracao = 0;

      $save->salva();
      
      $pag->add ("<br><font class=fontgray><font color=red><center>$lang[dados_salvos]</font></font></center><br><br>");
      
    }

    //apaga a variavel com codigo da area pro ambiente voltar pra selecao
    unset($_REQUEST[frm_codArea]);
  }


  if (!empty($_REQUEST[frm_codArea])) {  
    //se frm_codArea nao tiver vazio eh porque uma categ jah foi selecionada
    $campos_ausentes = "";
    $campos_hidden = array("codArea", "intGeracao");
    $form = new WSmartForm("AMArea","form1","editar_areas.php?acao=A_salvar",$campos_ausentes,$campos_hidden);
    $form->setCancelUrl("editar_areas.php");
    $form->setDesign(WFORMEL_DESIGN_OVER);
    $form->setLabelClass("fontgray");

    $areas = $_SESSION[ambiente]->listaAreas();
    $form->setSelect("codPai",$areas,"codArea","nomArea");
    $form->componentes[codPai]->addOption(0, $lang[nenhum_pai]);
    
    if ($_REQUEST[frm_codArea] != "new") {
      $area = new AMArea($_REQUEST[frm_codArea]);
      $form->loadDataFromObject($area);
    }
    
    //cria o form pra apagar

    $js = "function apaga() {";
    $js .= "if (confirm('$lang[confirma_apagar_area]')) document.formApaga.submit();}";

    $pag->addScript($js);

    $apaga = "<form method=post name=\"formApaga\" action=\"editar_areas.php\">";
    $apaga .= "<input type=hidden name=\"acao\" value=\"A_apaga\">";
    $apaga .= "<input type=hidden name=\"frm_codArea\" value=\"".$_REQUEST[frm_codArea]."\">";
    $apaga .= "<table width=\"100%\"><tr><td width=\"350\"><center>";
    $apaga .= "<input type=button onClick=\"apaga();\" value=\"$lang[apagar_area]\">";
    $apaga .= "</center></td><td>&nbsp;</td></tr></table>";
    $apaga .= "</form>";

    $pag->add ($form);
    if ($_REQUEST[frm_codArea] != "new") $pag->add ($apaga);
  }
    
  //se nenhuma categ tiver sido selecionada, abre a lista
  else {
    //lista as categs com as opcoes
    $categ = $_SESSION[ambiente]->listaAreas();
    
    if (!empty($categ->records)) {
      
      $box = new AMBox();
      $box->setTitle($lang[areas]);
      
      foreach ($categ->records as $cat) {
	$link = "<a href=\"editar_areas.php?acao=editar&frm_codArea=".$cat->codArea."\" class=fontgray>".$cat->nomArea."</a>";
	$box->addItem($link);
      }

    $pag->add ($box);
    }
    
    else $pag->add ("<font class=fontgray>".$lang[nenhuma_area]."</font>");
  }


  $pag->imprime();
  
}

else die($lang[acesso_nao_permitido]);


?>
