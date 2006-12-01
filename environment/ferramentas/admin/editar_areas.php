<?php

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

//soh imprime se o usuario for super
//if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {
$box = new AMBAdminAreas;

$pag->add($box);

echo $pag;
//}
//else die($lang[acesso_nao_permitido]);


?>
