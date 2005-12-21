<?php

include_once("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("admin");

$pag = new AMTAdmin();

//soh imprime se o usuario for super
//if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {
$pag->add("Editar Categorias:<br>");

//primeiro  mostra as categorias existentes


  
//}

//else die($lang[acesso_nao_permitido]);

echo $pag;
?>
