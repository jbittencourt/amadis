<?
include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

//soh imprime se o usuario for super
//if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {

$box = new AMBAdminChgPw;

$box1 = new AMTwoColsLayout;

$box1->add($box, AMTwoColsLayout::LEFT);

$pag->add($box1);

//}
//else die($lang[acesso_nao_permitido]);

echo $pag; 


?>