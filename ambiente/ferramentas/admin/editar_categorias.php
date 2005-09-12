<?php

include_once("../../config.inc.php");
include_once("$pathuserlib/amadmin.inc.php");
include_once("$pathuserlib/amuser.inc.php");
include_once("$pathuserlib/amcategoria.inc.php");

include_once("$pathtemplates/amtadmin.inc.php");
include_once("$pathtemplates/ambox.inc.php");

include_once("$rdpath/smartform/wsmartform.inc.php");

$ui = new RDui("admin");
$lang = $_SESSION[ambiente]->getLangUi($ui);

$pag = new AMTAdmin();

//soh imprime se o usuario for super
if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {

  //cria o sub-menu
  if (empty($_REQUEST[frm_codCategoria]) or ($_REQUEST[acao] == "A_apaga") or ($_REQUEST[acao] == "A_salvar")) {
    $itens[$lang[voltar_admin]] = "admin.php";
    $itens[$lang[criar_categoria]] = "editar_categorias.php?frm_codCategoria=new";
  }
  
  else $itens[$lang[selecionar_outra_categoria]] = "editar_categorias.php";
  
  $pag->setSubMenu($itens);
  $pag->add ("<br>");
  

  //salva os dados postados do formulario
  if ($_REQUEST[acao] == "A_apaga") {
    $apaga = new AMCategoria($_REQUEST[frm_codCategoria]);
    $users = $apaga->listaUsuarios();

    //nao permite que se apague a categoria se tiver usuario cadastrado nela
    if (!empty($users->records)) {
      $pag->add ("<center><font class=fontgray color=red size=+1>$lang[existem_users_categoria]</font></center><br><br>");
    }

    else {
      $apaga->deleta();
      $pag->add ("<center><font class=fontgray color=red size=+1>$lang[categoria_apagada]</font></center><br><br>");
    }

    unset ($_REQUEST[frm_codCategoria]);
  }

  if($_REQUEST[acao] == "A_salvar") {
    
    $save = new AMCategoria($_REQUEST[frm_codCategoria]);

    //verifica se a categoria jah existe caso esteja sendo cadsatrada uma nova cat.
    if ($save->nomCategoria == "") {
      if ($_SESSION[ambiente]->existeCategoria($_REQUEST[frm_nomCategoria]) == "1") {
	$notSave = "1";
	$pag->add ("<br><font class=fontgray><font color=red size=+1><center>$lang[categoria_jah_existe]</font></font></center><br><br>");
      }
    }

    //seta os valores do objeto e salva
    if ($notSave != "1") {
      $save->nomCategoria = $_REQUEST[frm_nomCategoria];
      $save->flaPublica = $_REQUEST[frm_flaPublica];
      $save->salva();
      
      $pag->add ("<br><font class=fontgray><font color=red><center>$lang[dados_salvos]</font></font></center><br><br>");
      
    }

    //apaga a variavel com codigo da categoria pro ambiente voltar pra selecao
    unset($_REQUEST[frm_codCategoria]);
  }


  if (!empty($_REQUEST[frm_codCategoria])) {  
    //se frm_codCategoria nao tiver vazio eh porque uma categ jah foi selecionada
    $campos_ausentes = "";
    $campos_hidden = array("codCategoria");
    $form = new WSmartForm("AMCategoria","form1","editar_categorias.php",$campos_ausentes,$campos_hidden);

    $form->addComponent("acao", new WHidden("acao","A_salvar"));

    $options[] = $lang[privada];
    $options[] = $lang[publica];

    $form->setSelect("flaPublica",$options);
    $form->setCancelUrl("editar_categorias.php");
    $form->setDesign(WFORMEL_DESIGN_OVER);
    $form->setLabelClass("fontgray");

    
    if ($_REQUEST[frm_codCidade] != "new") {
      $categ = new AMCategoria($_REQUEST[frm_codCategoria]);
      $form->loadDataFromObject($categ);
    }
    
    //cria o form pra apagar

    $js = "function apaga() {";
    $js .= "if (confirm('$lang[confirma_apagar_categoria]')) document.formApaga.submit();}";

    $pag->addScript($js);

    $apaga = "<form method=post name=\"formApaga\" action=\"editar_categorias.php\">";
    $apaga .= "<input type=hidden name=\"acao\" value=\"A_apaga\">";
    $apaga .= "<input type=hidden name=\"frm_codCategoria\" value=\"".$_REQUEST[frm_codCategoria]."\">";
    $apaga .= "<table width=\"100%\"><tr><td width=\"240\"><center>";
    $apaga .= "<input type=button onClick=\"apaga();\" value=\"$lang[apagar_categoria]\">";
    $apaga .= "</center></td><td>&nbsp;</td></tr></table>";
    $apaga .= "</form>";

    $pag->add ($form);
    if ($_REQUEST[frm_codCategoria] != "new") $pag->add ($apaga);
  }
    
  //se nenhuma categ tiver sido selecionada, abre a lista
  else {
    //lista as categs com as opcoes
    $categ = $_SESSION[ambiente]->listaCategorias();
    
    if (!empty($categ->records)) {
      
      $box = new AMBox();
      $box->setTitle($lang[categorias]);
      
      foreach ($categ->records as $cat) {
	$link = "<a href=\"editar_categorias.php?acao=editar&frm_codCategoria=".$cat->codCategoria."\" class=fontgray>".$cat->nomCategoria."</a>";
	$box->addItem($link);
      }

    $pag->add ($box);
    }
    
    else $pag->add ("<font class=fontgray>".$lang[nenhuma_categoria]."</font>");
  }


  $pag->imprime();
  
}

else die($lang[acesso_nao_permitido]);


?>
