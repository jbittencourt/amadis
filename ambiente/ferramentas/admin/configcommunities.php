<?

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

$pag->add("<br><br>");

$box = new AMBEditCommunities;

$pag->add($box);

echo $pag;

?>