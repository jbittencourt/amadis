<?php

include_once("../../config.inc.php");
include_once("$pathuserlib/amadmin.inc.php");
include_once("$pathuserlib/amescola.inc.php");
include_once("$pathuserlib/amcidade.inc.php");
include_once("$pathuserlib/amuser.inc.php");

include_once("$pathtemplates/amtadmin.inc.php");
include_once("$pathtemplates/ambox.inc.php");

include_once("$rdpath/smartform/wsmartform.inc.php");

$ui = new RDui("admin");
$lang = $_SESSION[ambiente]->getLangUi($ui);

$pag = new AMTAdmin();

//soh imprime se o usuario for super
if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {

  //cria o sub-menu
  if (empty($_REQUEST[frm_codCidade]) or ($_REQUEST[acao] == "A_apaga") or ($_REQUEST[acao] == "A_salvar")) {
    $itens[$lang[voltar_admin]] = "admin.php";
    $itens[$lang[criar_cidade]] = "editar_cidades.php?frm_codCidade=new";
  }
  
  else $itens[$lang[selecionar_outra_cidade]] = "editar_cidades.php";
  
  $pag->setSubMenu($itens);
  $pag->add ("<br>");
  

  //salva os dados postados do formulario
  if ($_REQUEST[acao] == "A_apaga") {
    $apaga = new AMCidade($_REQUEST[frm_codCidade]);
    $escolas = $apaga->listaEscolas();
    $users = $apaga->listaUsuarios();

    //nao permite que se apague a cidade se tiver usuario ou escola cadastrado nela
    if (!empty($users->records)) {
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[existem_users_cidade]</font></font></center><br><br>");
    }

    if (!empty($escolas->records)) {
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[existem_escolas_cidade]</font></font></center><br><br>");
    }

    else {
      $apaga->deleta();
      $pag->add ("<center><font class=fontgray><font color=red size=+1>$lang[cidade_apagada]</font></font></center><br><br>");
    }

    unset ($_REQUEST[frm_codCidade]);
  }

  if($_REQUEST[acao] == "A_salvar") {

    $save = new AMCidade($_REQUEST[frm_codCidade]);

    //verifica se a escola jah existe caso esteja sendo cadsatrada uma nova escola
    if ($save->nomCidade == "") {
      if ($_SESSION[ambiente]->existeCidade($_REQUEST[frm_nomCidade]) == "1") {
	$notSave = "1";
	$pag->add ("<br><font class=fontgray><font color=red size=+1><center>$lang[cidade_jah_existe]</font></font></center><br><br>");
      }
    }

    //seta os valores do objeto e salva
    if ($notSave != "1") {
      $save->nomCidade = $_REQUEST[frm_nomCidade];
      $save->codEstado = $_REQUEST[frm_codEstado];
      $save->tempo = time();
      $save->salva();
      
      $pag->add ("<br><font class=fontgray><font color=red><center>$lang[dados_salvos]</font></font></center><br><br>");
      
    }

    //apaga a variavel com codigo da escola pro ambiente voltar pra selecao de escolas
    unset($_REQUEST[frm_codCidade]);
  }


  if (!empty($_REQUEST[frm_codCidade])) {  
    //se frm_codCidade nao tiver vazio eh porque uma cidade jah foi selecionada
    $campos_ausentes = array("tempo");
    $campos_hidden = array("codCidade");
    $form = new WSmartForm("AMCidade","form1","editar_cidades.php?acao=A_salvar",$campos_ausentes,$campos_hidden);
    $lista = new RDLista("AMEstado");
    $form->setSelect("codEstado",$lista,"codEstado","nomEstado");
    $form->setCancelUrl("editar_cidades.php");
    $form->setDesign(WFORMEL_DESIGN_OVER);
    $form->setLabelClass("fontgray");

    if ($_REQUEST[frm_codCidade] != "new") {
      $cidade = new AMCidade($_REQUEST[frm_codCidade]);
      $form->loadDataFromObject($cidade);
    }
    
    //cria o form pra apagar

    $js = "function apaga() {";
    $js .= "if (confirm('$lang[confirma_apagar_cidade]')) document.formApaga.submit();}";

    $pag->addScript($js);

    $apaga = "<form method=post name=\"formApaga\" action=\"editar_cidades.php\">";
    $apaga .= "<input type=hidden name=\"acao\" value=\"A_apaga\">";
    $apaga .= "<input type=hidden name=\"frm_codCidade\" value=\"".$_REQUEST[frm_codCidade]."\">";
    $apaga .= "<table width=\"100%\"><tr><td width=\"410\"><center>";
    $apaga .= "<input type=button onClick=\"apaga();\" value=\"$lang[apagar_cidade]\">";
    $apaga .= "</center></td><td>&nbsp;</td></tr></table>";
    $apaga .= "</form>";

    $pag->add ($form);
    if ($_REQUEST[frm_codCidade] != "new") $pag->add ($apaga);
  }
  
  
  //se nenhuma cidade tiver sido selecionada, abre a lista de cidades
  else {
    //lista as cidades com as opcoes
    $cidades = $_SESSION[ambiente]->listaCidades();
    
    if (!empty($cidades->records)) {
      
      $box = new AMBox();
      $box->setTitle($lang[cidades]);
      
      foreach ($cidades->records as $cidade) {
	$link = "<a href=\"editar_cidades.php?acao=editar&frm_codCidade=".$cidade->codCidade."\" class=fontgray>".$cidade->nomCidade."&nbsp;(".$cidade->desSigla.")</a>";
	$box->addItem($link);
      }

    $pag->add ($box);
    }
    
    else $pag->add ("<font class=fontgray>".$lang[nenhuma_cidade]."</font>");
  }


  $pag->imprime();
  
}

else die($lang[acesso_nao_permitido]);


?>
