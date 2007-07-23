<?php

include_once("../../config.inc.php");
include_once("$pathuserlib/amadmin.inc.php");
include_once("$pathuserlib/amescola.inc.php");
include_once("$pathuserlib/amcidade.inc.php");

include_once("$pathtemplates/amtadmin.inc.php");
include_once("$pathtemplates/ambox.inc.php");

include_once("$rdpath/smartform/wsmartform.inc.php");

$ui = new RDui("admin");
$lang = $_SESSION[environment]->getLangUi($ui);

$pag = new AMTAdmin();

//carrega os dados da escola selecionada
if (isset($_REQUEST[frm_codEscola])) {
  $escola = new AMEscola($_REQUEST[frm_codEscola]);

  //ve se o cara pode ou nao apagar a escola
  if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) $canErase = 1;
  else $canErase = 0;
}


//soh imprime se o usuario for super ou se for admin da escola
if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA) || 
    (isset($_REQUEST[frm_codEscola]) and $escola->ehAdmin($_SESSION[usuario]->codUser))) {

  //cria o sub-menu
  //se nao tiver escola selecionada
  if (empty($_REQUEST[frm_codEscola]) or ($_REQUEST[acao] == "A_apaga") or ($_REQUEST[acao] == "A_salvar")) {

    //verifica a url pra depois de salvar os dados redirecionar o usuario para lah
    $itens[$lang[voltar_admin]] = "admin.php";
    $itens[$lang[criar_escola]] = "editar_escolas.php?frm_codEscola=new";
  }
  
  //se jah tiver selecionado a escola
  else {

    //se for o admin da escola
    if ($_REQUEST[voltar] == "escola") {
      $voltar = "$urlferramentas/comunidade/escola.php?frm_codEscola=".$escola->codEscola;
      $option = "&voltar=escola";
      $itens[$lang[voltar_pagina_escola]] = $voltar;
    }

    //se for o admin do ambiente acessando atraves do menu administracao
    else {
      $voltar = "editar_escolas.php";
      $itens[$lang[selecionar_outra_escola]] = $voltar;
    }

  }
  
  $pag->setSubMenu($itens);
  $pag->add ("<br />");
  

  //salva os dados postados do formulario
  if ($_REQUEST[acao] == "A_apaga") {
    $apaga = new AMEscola($_REQUEST[frm_codEscola]);
    $users = $apaga->listaUsuarios();

    //avisa q tem gente na escola e nao deixa apagar
    if (!empty($users->records)) {
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[existem_alunos_escola]</font></font></center><br /><br />");
      $aviso = "?erro=alunos";
    }

    else {
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[escola_apagada]</font></font></center><br /><br />");
      $apaga->deleta();
    }

    //se o usuario tiver dentro do admin, lista as escolas denovo pra ele selecionar outra
    //apaga a variavel com codigo da escola pro ambiente voltar pra selecao de escolas
    unset($_REQUEST[frm_codEscola]);

    //se ele for soh o administrador da escola, volta para os dados dela
    if ($_REQUEST[voltar] == "escola") {
      if (empty($aviso)) header("Location: $urlferramentas/comunidade/comunidade.php");
      else header("Location: $urlferramentas/comunidade/escola.php".$aviso."&frm_codEscola=".$apaga->codEscola);
    }
  }

  if($_REQUEST[acao] == "A_salvar") {

    $save = new AMEscola($_REQUEST[frm_codEscola]);

    //verifica se a escola jah existe caso esteja sendo cadsatrada uma nova escola
    if ($save->nomEscola == "") {
      if ($_SESSION[environment]->existeEscola($_REQUEST[frm_nomEscola]) == "1") {
	$notSave = "1";
	$pag->add ("<br /><font class=fontgray><font color=red size=+1><center>$lang[escola_jah_existe]</font></font></center><br /><br />");
      }
    }

    //seta os valores do objeto e salva
    if ($notSave != "1") {
      $save->nomEscola = $_REQUEST[frm_nomEscola];
      $save->desEndereco = $_REQUEST[frm_desEndereco];
      $save->codCidade = $_REQUEST[frm_codCidade];
      $save->desBairro = $_REQUEST[frm_desBairro];
      $save->desTelefone = $_REQUEST[frm_desTelefone];
      $save->salva();
      
      $pag->add ("<br /><font class=fontgray><font color=red><center>$lang[dados_salvos]</font></font></center><br /><br />");
      
    }

    //se o usuario tiver dentro do admin, lista as escolas denovo pra ele selecionar outra
    //apaga a variavel com codigo da escola pro ambiente voltar pra selecao de escolas
    unset($_REQUEST[frm_codEscola]);

    //se ele for soh o administrador da escola, volta para os dados dela
    if ($_REQUEST[voltar] == "escola") header("Location: $voltar");
  }


  if (!empty($_REQUEST[frm_codEscola])) {  
    //se frm_codEscola nao tiver vazio eh porque uma escola jah foi selecionada
    $campos_ausentes = array();
    $campos_hidden = array("codEscola");
    $form = new WSmartForm("AMEscola","form1","editar_escolas.php?acao=A_salvar".$option,$campos_ausentes,$campos_hidden);
    $lista = $_SESSION[environment]->listaCidades();
    $form->setSelect("codCidade",$lista,"codCidade","nomCidade");
    $form->setCancelUrl($voltar);
    $form->setDesign(WFORMEL_DESIGN_OVER);
    $form->setLabelClass("fontgray");

    
    if ($_REQUEST[frm_codEscola] != "new") {
      $escola = new AMEscola($_REQUEST[frm_codEscola]);
      $form->loadDataFromObject($escola);
    }
    
    //cria o form pra apagar

    $js = "function apaga() {";
    $js .= "if (confirm('$lang[confirma_apagar_escola]')) document.formApaga.submit();}";

    $pag->addScript($js);

    $apaga = "<form method=post name=\"formApaga\" action=\"editar_escolas.php\">";
    $apaga .= "<input type=hidden name=\"acao\" value=\"A_apaga\">";
    $apaga .= "<input type=hidden name=\"frm_codEscola\" value=\"".$_REQUEST[frm_codEscola]."\">";
    if ($_REQUEST[voltar] == "escola")
      $apaga .= "<input type=hidden name=\"voltar\" value=\"escola\">";
    $apaga .= "<table width=\"100%\"><tr><td width=\"415\"><center>";
    $apaga .= "<input type=button onClick=\"apaga();\" value=\"$lang[apagar_escola]\">";
    $apaga .= "</center></td><td>&nbsp;</td></tr></table>";
    $apaga .= "</form>";

    $pag->add ($form);
    if ($_REQUEST[frm_codEscola] != "new" and $canErase == "1") $pag->add ($apaga);
  }
  
  
  //se nenhuma escola tiver sido selecionada, abre a lista de escolas.
  else {
    //lista as escolas com as opcoes
    $escolas = $_SESSION[environment]->listaEscolas();
    
    if (!empty($escolas->records)) {
      
      $box = new AMBox();
      $box->setTitle($lang[escolas]);
      
      foreach ($escolas->records as $escola) {
	$link = "<a href=\"editar_escolas.php?acao=editar&frm_codEscola=".$escola->codEscola."\" class=fontgray>".$escola->nomEscola."</a>";
	$box->addItem($link);
      }

    $pag->add ($box);
    }
    
    else $pag->add ("<font class=fontgray>".$lang[nenhuma_escola]."</font>");
  }


  $pag->imprime();
  
}

else die($lang[acesso_nao_permitido]);