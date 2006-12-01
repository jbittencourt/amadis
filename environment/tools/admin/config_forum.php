<?php

include_once("../../config.inc.php");
include_once("$pathuserlib/amadmin.inc.php");
include_once("$pathuserlib/amforum.inc.php");

include_once("$pathtemplates/amtadmin.inc.php");
include_once("$pathtemplates/ambox.inc.php");

include_once("$rdpath/smartform/wsmartform.inc.php");

$ui = new RDui("config_forum");
$lang = $_SESSION[environment]->getLangUi($ui);

if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {
  $pag = new AMTAdmin();

  if ($_REQUEST[acao] == "A_salvar") {
    if ($_REQUEST[frm_enable] != $_SESSION[config]->forum[enable]) {
      $chave = array();
      $chave[] = opVal("desGrupo", "forum");
      $chave[] = opVal("desCampo", "enable");
      $item = new AMConfigItem($chave);
      $item->desValor = $_REQUEST[frm_enable];
      $item->salva();
    }
  
    if ($_REQUEST[frm_before] != $_SESSION[config]->forum[show_create_before_list]) {
      $chave = array();
      $chave[] = opVal("desGrupo", "forum");
      $chave[] = opVal("desCampo", "show_create_before_list");
      $item = new AMConfigItem($chave);
      $item->desValor = $_REQUEST[frm_before];
      $item->salva();
    }

    if ($_REQUEST[frm_mode] != $_SESSION[config]->forum[default_message_mode]) {
      $chave = array();
      $chave[] = opVal("desGrupo", "forum");
      $chave[] = opVal("desCampo", "default_message_mode");
      $item = new AMConfigItem($chave);
      $item->desValor = $_REQUEST[frm_mode];
      $item->salva();
    }

    if ($_REQUEST[num_title] != $_SESSION[config]->forum[messages_title_mode]) {
      $chave = array();
      $chave[] = opVal("desGrupo", "forum");
      $chave[] = opVal("desCampo", "messages_title_mode");
      $item = new AMConfigItem($chave);
      $item->desValor = $_REQUEST[num_title];
      $item->salva();
    }

    if ($_REQUEST[num_full] != $_SESSION[config]->forum[messages_full_mode]) {
      $chave = array();
      $chave[] = opVal("desGrupo", "forum");
      $chave[] = opVal("desCampo", "messages_full_mode");
      $item = new AMConfigItem($chave);
      $item->desValor = $_REQUEST[num_full];
      $item->salva();
    }
    $_SESSION[config]->reload();
    header("Location: config_forum.php");
  }
  
  $pag->add ("<br>");

  $tab = new AMBox();
  $tab->setTitle($lang[config_forum]);

  $tab->add ("<form method=post action=\"config_forum.php\">");
  $tab->add ("<input type=hidden name=\"acao\" value=\"A_salvar\">");

  $form = new WSmartForm("", "config","config_forum.php");
  $form->setCancelOff();
  $form->setDesign(WFORMEL_DESIGN_STRING_DEFINED);
  $form->setLabelClass("fontgray");
  $form->submit_label = $lang[salvar];

  //campo oculto dizendo a acao
  $form->addComponent("acao", new WHidden("acao","A_salvar"));

  //campo ativo sim ou nao
  $form->addComponent("enable", new WRadioGroup("enable"));
  $form->componentes[enable]->addOption(1, $lang[sim]);
  $form->componentes[enable]->addOption(0, $lang[nao]);
  $form->componentes[enable]->setValue($_SESSION[config]->forum[enable]);
  $form->componentes[enable]->radios["0"]->design=WFORMEL_DESIGN_SIDE_RIGTH;
  $form->componentes[enable]->radios["1"]->design=WFORMEL_DESIGN_SIDE_RIGTH;
  $form->componentes[enable]->radioDesign = WRADIO_DESIGN_SIDE;
  $form->componentes[enable]->fdesign = WFORMEL_DESIGN_STRING_DEFINED;

  //campo criar antes ou depois da listagem
  $form->addComponent("before", new WRadioGroup("before"));
  $form->componentes[before]->addOption("1", $lang[antes]);
  $form->componentes[before]->addOption("0", $lang[depois]);
  $form->componentes[before]->setValue($_SESSION[config]->forum[show_create_before_list]);
  $form->componentes[before]->radios["0"]->design=WFORMEL_DESIGN_SIDE_RIGTH;
  $form->componentes[before]->radios["1"]->design=WFORMEL_DESIGN_SIDE_RIGTH;
  $form->componentes[before]->radioDesign = WRADIO_DESIGN_SIDE;
  $form->componentes[before]->fdesign = WFORMEL_DESIGN_STRING_DEFINED;

  //maneira padrao de listagem
  $form->addComponent("mode",new WRadioGroup("mode"));
  $form->componentes[mode]->addOption("title", $lang[title_mode]);
  $form->componentes[mode]->addOption("full", $lang[full_mode]);
  $form->componentes[mode]->setValue($_SESSION[config]->forum[default_message_mode]);
  $form->componentes[mode]->radios["full"]->design=WFORMEL_DESIGN_SIDE_RIGTH;
  $form->componentes[mode]->radios["title"]->design=WFORMEL_DESIGN_SIDE_RIGTH;
  $form->componentes[mode]->fdesign = WFORMEL_DESIGN_STRING_DEFINED;

  //numero de mensagens title mode
  $form->addComponent("num_title",new WText("num_title", "", 4, 4));
  $form->componentes[num_title]->setValue($_SESSION[config]->forum[messages_title_mode]);

  //numero de mensagens full mode
  $form->addComponent("num_full",new WText("num_full", "", 4, 4));
  $form->componentes[num_full]->setValue($_SESSION[config]->forum[messages_full_mode]);

  $a = "<tr><td width=\"60%\" valign=middle class=fontgray>";
  $b = "</td><td width=\"40%\" align=left class=fontgray>";
  $bl = "</td><td width=\"40%\" align=center class=fontgray>";
  $c = "</td></tr>";

  $str = "<table width=\"100%\">";
  $str .= "<tr><td colspan=2 class=fontcolor>$lang[config_gerais]</td></tr>";
  $str .= "$a{LABEL_FRM_ENABLE}&nbsp;&nbsp;{TIP_FRM_ENABLE}$b{FORM_EL_FRM_ENABLE}$c";

  $str .= "<tr><td colspan=2 class=fontcolor>$lang[config_lista_foruns]</td></tr>";
  $str .= "$a{LABEL_FRM_BEFORE}&nbsp;&nbsp;{TIP_FRM_BEFORE}$b{FORM_EL_FRM_BEFORE}$c";
  $str .= "$a{LABEL_FRM_MODE}&nbsp;&nbsp;{TIP_FRM_MODE}$b{FORM_EL_FRM_MODE}$c";

  $str .= "<tr><td colspan=2 class=fontcolor>$lang[config_lista_mensagens]</td></tr>";
  $str .= "$a{LABEL_FRM_NUM_TITLE}&nbsp;&nbsp;{TIP_FRM_NUM_TITLE}$b{FORM_EL_FRM_NUM_TITLE}$c";
  $str .= "$a{LABEL_FRM_NUM_FULL}&nbsp;&nbsp;{TIP_FRM_NUM_FULL}$b{FORM_EL_FRM_NUM_FULL}$c";
  $str .= "$a&nbsp;$bl{FORM_EL_SUBMIT_BUTTONS}$c";
  $str .= "</table>";

  $form->setDesignString($str,1);

  $tab->add ($form);
  $pag->add ($tab);
  $pag->imprime();

}
else die($lang[acesso_negado]);

?>
