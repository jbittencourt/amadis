<?php

include_once("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("admin");

$pag = new AMTAdmin();

$box = new AMTwoColsLayout;
//soh imprime se o usuario for super
//if ($_SESSION[user]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {


//permite a mudanca de dados das tabelas estaticas do ambiente
$box->add(new AMBAdminTables, AMTwoColsLayout::LEFT);
$box->add ("<br>", AMTwoColsLayout::LEFT);

$box->add(new AMBAdminConfigEvents, AMTwoColsLayout::LEFT);


//segunda coluna
$box->add(new AMBAdminConfig, AMTwoColsLayout::RIGHT);

//}

//else $pag->add($lang[access_denied]);

$pag->add($box);

echo $pag;

?>
