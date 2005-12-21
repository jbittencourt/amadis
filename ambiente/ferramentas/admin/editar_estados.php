<?php

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();


//soh imprime se o usuario for super
//if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {
$pag->add("<< Voltar");
$box = new AMTwoColsLayout;

$box2 = new AMBAdminCidades;

$box1 = new AMBAdminEstados;

$box->add($box1,AMTwoColsLayout::LEFT);
$box->add($box2,AMTwoColsLayout::RIGHT);
$pag->add($box);

//}
//else die($lang[acesso_nao_permitido]); 

echo $pag;

?>
