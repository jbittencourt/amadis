<?php

include_once("../../config.inc.php");
include_once("$pathuserlib/amadmin.inc.php");
include_once("$pathuserlib/amprojeto.inc.php");

include_once("$pathtemplates/amtadmin.inc.php");
include_once("$pathtemplates/ambox.inc.php");

include_once("$rdpath/smartform/wsmartform.inc.php");

$ui = new RDui("admin");
$lang = $_SESSION[ambiente]->getLangUi($ui);

$pag = new AMTAdmin();

 //soh imprime se o usuario for super
if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {

  //cria o sub-menu
  if (empty($_REQUEST[frm_codStatus]) or ($_REQUEST[acao] == "A_apaga") or ($_REQUEST[acao] == "A_salvar")) {
    $itens[$lang[voltar_admin]] = "admin.php";
    $itens[$lang[criar_status]] = "editar_status.php?frm_codStatus=new";
  }
  
  else $itens[$lang[selecionar_outro_status]] = "editar_status.php";
  
  $pag->setSubMenu($itens);
  $pag->add ("<br>");

  //salva os dados postados do formulario
  if ($_REQUEST[acao] == "A_apaga") {
    $apaga = new AMProjectStatus($_REQUEST[frm_codStatus]);
    $projs = $apaga->listaProjetos();

    //nao permite que se apague a area se tiver usuario cadastrado nela
    if (!empty($projs->records)) {
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[existem_projs_status]</font></font></center><br><br>");
    }

    else {
      $apaga->deleta();
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[status_apagada]</font></font></center><br><br>");
    }

    unset ($_REQUEST[frm_codStatus]);
  }

  if($_REQUEST[acao] == "A_salvar") {

    $save = new AMProjectStatus($_REQUEST[frm_codStatus]);

    //verifica se a area jah existe caso esteja sendo cadsatrada uma nova area
    if ($save->nomArea == "") {
      if ($_SESSION[ambiente]->existeStatus($_REQUEST[frm_desStatus]) == "1") {
	$notSave = "1";
	$pag->add ("<br><font class=fontgray><font color=red size=+1><center>$lang[status_jah_existe]</font></font></center><br><br>");
      }
    }

    //seta os valores do objeto e salva
    if ($notSave != "1") {
      $save->desStatus = $_REQUEST[frm_desStatus];

      $save->salva();
      
      $pag->add ("<br><font class=fontgray><font color=red><center>$lang[dados_salvos]</font></font></center><br><br>");
      
    }

    //apaga a variavel com codigo da area pro ambiente voltar pra selecao
    unset($_REQUEST[frm_codStatus]);
  }


  if (!empty($_REQUEST[frm_codStatus])) {  
    //se frm_codStatus nao tiver vazio eh porque um status  jah foi selecionado
    $campos_ausentes = "";
    $campos_hidden = array("codStatus");
    $form = new WSmartForm("AMProjectStatus","form1","editar_status.php?acao=A_salvar",$campos_ausentes,$campos_hidden);
    $form->setCancelUrl("editar_status.php");
    $form->setDesign(WFORMEL_DESIGN_OVER);
    $form->setLabelClass("fontgray");

    if ($_REQUEST[frm_codStatus] != "new") {
      $area = new AMProjectStatus($_REQUEST[frm_codStatus]);
      $form->loadDataFromObject($area);
    }
    
    //cria o form pra apagar

    $js = "function apaga() {";
    $js .= "if (confirm('$lang[confirma_apagar_status]')) document.formApaga.submit();}";

    $pag->addScript($js);

    $apaga = "<form method=post name=\"formApaga\" action=\"editar_status.php\">";
    $apaga .= "<input type=hidden name=\"acao\" value=\"A_apaga\">";
    $apaga .= "<input type=hidden name=\"frm_codStatus\" value=\"".$_REQUEST[frm_codStatus]."\">";
    $apaga .= "<table width=\"100%\"><tr><td width=\"350\"><center>";
    $apaga .= "<input type=button onClick=\"apaga();\" value=\"$lang[apagar_status]\">";
    $apaga .= "</center></td><td>&nbsp;</td></tr></table>";
    $apaga .= "</form>";

    $pag->add ($form);
    if ($_REQUEST[frm_codStatus] != "new") $pag->add ($apaga);
  }
    
  //se nenhuma categ tiver sido selecionada, abre a lista
  else {
    //lista as categs com as opcoes
    $categ = $_SESSION[ambiente]->listaStatus();
    
    if (!empty($categ->records)) {
      
      $box = new AMBox();
      $box->setTitle($lang[status]);
      
      foreach ($categ->records as $cat) {
	$link = "<a href=\"editar_status.php?acao=editar&frm_codStatus=".$cat->codStatus."\" class=fontgray>".$cat->desStatus."</a>";
	$box->addItem($link);
      }

    $pag->add ($box);
    }
    
    else $pag->add ("<font class=fontgray>".$lang[nenhum_status]."</font>");
  }


  $pag->imprime();
  
}

else die($lang[acesso_nao_permitido]);


?>
