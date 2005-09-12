<?php

include_once("../../config.inc.php");
include_once("$pathuserlib/amadmin.inc.php");
include_once("$pathuserlib/amestado.inc.php");

include_once("$pathtemplates/amtadmin.inc.php");
include_once("$pathtemplates/ambox.inc.php");

include_once("$rdpath/smartform/wsmartform.inc.php");

$ui = new RDui("admin");
$lang = $_SESSION[ambiente]->getLangUi($ui);

$pag = new AMTAdmin();

//soh imprime se o usuario for super
if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {

  //cria o sub-menu
  if (empty($_REQUEST[frm_codEstado])  or ($_REQUEST[acao] == "A_apaga") or ($_REQUEST[acao] == "A_salvar")) {
    $itens[$lang[voltar_admin]] = "admin.php";
    $itens[$lang[criar_estado]] = "editar_estados.php?frm_codEstado=new";
  }
  else $itens[$lang[selecionar_outro_estado]] = "editar_estados.php";
  
  $pag->setSubMenu($itens);
  $pag->add ("<br>");
  
  //quando eh selecionado apagar estado
  if ($_REQUEST[acao] == "A_apaga") {
    $apaga = new AMEstado($_REQUEST[frm_codEstado]);
    $cidades = $apaga->listaCidades();

    //nao permite que o estado seja apagado se tiverem cidades cadastradas nele
    if (!empty($cidades->records)) {
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[existem_cidades_estado]</font></font></center><br><br>");
    }

    else {
      $apaga->deleta();
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[estado_apagado]</font></font></center><br><br>");
    }

    unset ($_REQUEST[frm_codEscola]);
  }


  if($_REQUEST[acao] == "A_salvar") {
    $save = new AMEstado($_REQUEST[frm_codEstado]);

    //verifica se a escola jah existe caso esteja sendo cadsatrada uma nova escola
    if ($save->nomEstado == "") {
      if ($_SESSION[ambiente]->existeEstado($_REQUEST[frm_nomEstado]) == "1") {
	$notSave = "1";
	$pag->add ("<br><font class=fontgray><font color=red size=+1><center>$lang[estado_jah_existe]</font></font></center><br><br>");
      }
    }

    //seta os valores do objeto e salva
    if ($notSave != "1") {
      $save->nomEstado = $_REQUEST[frm_nomEstado];
      $save->desSigla = $_REQUEST[frm_desSigla];
      $save->desPais = $_REQUEST[frm_desPais];
      $save->salva();

      $pag->add ("<br><font class=fontgray><font color=red><center>$lang[dados_salvos]</font></font></center><br><br>");
    }

    //limpa esse valor pro ambiente voltar pra tela de selecao de estados
    unset($_REQUEST[frm_codEstado]);
  }


  if (!empty($_REQUEST[frm_codEstado])) {  
    //se frm_codEstado nao tiver vazio eh porque um estado jah foi selecionada
    $campos_ausentes = array();
    $campos_hidden = array("codEstado");
    $form = new WSmartForm("AMEstado","form1","editar_estados.php?acao=A_salvar",$campos_ausentes,$campos_hidden);
    $form->setCancelUrl("editar_estados.php");
    $form->setDesign(WFORMEL_DESIGN_OVER);
    $form->setLabelClass("fontgray");

    
    if ($_REQUEST[frm_codEstado] != "new") {
      $estado = new AMEstado($_REQUEST[frm_codEstado]);
      $form->loadDataFromObject($estado);
    }
    
    //cria o form pra apagar

    $js = "function apaga() {";
    $js .= "if (confirm('$lang[confirma_apagar_estado]')) document.formApaga.submit();}";

    $pag->addScript($js);

    $apaga = "<form method=post name=\"formApaga\" action=\"editar_escolas.php\">";
    $apaga .= "<input type=hidden name=\"acao\" value=\"A_apaga\">";
    $apaga .= "<input type=hidden name=\"frm_codEscola\" value=\"".$_REQUEST[frm_codEscola]."\">";
    $apaga .= "<table width=\"100%\"><tr><td width=\"290\"><center>";
    $apaga .= "<input type=button onClick=\"apaga();\" value=\"$lang[apagar_estado]\">";
    $apaga .= "</center></td><td>&nbsp;</td></tr></table>";
    $apaga .= "</form>";

    $pag->add ($form);
    if ($_REQUEST[frm_codEstado] != "new") $pag->add ($apaga);
  }
  
  
  //se nenhum estado tiver sido selecionado, abre a lista de estados.
  else {
    //lista os estados
    $estados = $_SESSION[ambiente]->listaEstados();
    
    if (!empty($estados->records)) {
      
      $box = new AMBox();
      $box->setTitle($lang[estados]);
      
      foreach ($estados->records as $estado) {
	$link = "<a href=\"editar_estados.php?frm_codEstado=".$estado->codEstado."\" class=fontgray>".$estado->nomEstado."&nbsp(".$estado->desSigla.")</a>";
	$box->addItem($link);
      }

    $pag->add ($box);
    }
    
    else $pag->add ("<font class=fontgray>".$lang[nenhum_estado]."</font>");
  }


  $pag->imprime();
  
}

else die($lang[acesso_nao_permitido]);


?>
