<?php

include_once("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("admin");

$pag = new AMTAdmin();

$box = new AMTwoColsLayout;

//soh imprime se o usuario for super
//if ($_SESSION[user]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {

$coreModules = new AMColorBox($_language['core_system'], AMColorBox::COLOR_BOX_PURPLE);
$coreModules->add('<a href="#">Modules</a><br />');
$coreModules->add('<a href="'.$_CMAPP['services_url'].'/admin/sendmessages.php">'.$_language['send_messages'].'</a><br />');
$coreModules->add('<a href="#">'.$_language['config_help'].'</a><br />');
$coreModules->add('<a href="'.$_CMAPP['services_url'].'/admin/viewlogs.php">'.$_language['view_logs'].'</a><br />');

$box->add($coreModules, AMTwoColsLayout::LEFT);

$adBox = new AMBAdminTables;
$box->add($adBox, AMTwoColsLayout::LEFT);

//segunda coluna
$box->add(new AMBAdminConfig, AMTwoColsLayout::RIGHT);

$box->add(new AMBAdminUsers, AMTwoColsLayout::RIGHT);
//}
//else{ $pag->add($_language[access_denied]);}


$pag->add($box);

echo $pag;

?>
