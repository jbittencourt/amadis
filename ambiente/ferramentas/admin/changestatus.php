<?
include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

$box = new AMBAdminChgStatus;
$box1 = new AMTwoColsLayout;

$box1->add($box, AMTwoColsLayout::LEFT);

$pag->add($box1);
//}

//else die($lang[acesso_nao_permitido]);

echo $pag; 

?>