<?php

include_once("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("admin");

$pag = new AMTAdmin();

$box = new AMTwoColsLayout;

//soh imprime se o usuario for super
//if ($_SESSION[user]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {


$adBox = new AMBAdminTables;
//permite a mudanca de dados das tabelas estaticas do ambiente
$box->add($adBox, AMTwoColsLayout::LEFT);
$box->add ("<br>", AMTwoColsLayout::LEFT);

$box->add(new AMBAdminConfigEvents, AMTwoColsLayout::LEFT);


//segunda coluna
$box->add(new AMBAdminConfig, AMTwoColsLayout::RIGHT);

$box->add(new AMBAdminUsers, AMTwoColsLayout::RIGHT);
//}
//else{ $pag->add($_language[access_denied]);}

$pag->add($box);

echo $pag;

?>
