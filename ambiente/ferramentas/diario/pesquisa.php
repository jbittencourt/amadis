<?
include("../../config.inc.php");
include($_CMAPP[path]."/lib/amdiariopost.inc.php");
include($_CMAPP[path]."/templates/amtdiario.inc.php");
include($_CMAPP[path]."/templates/amboxdiario.inc.php");
include($_CMAPP[path]."/lib/amdiariocomentario.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("diario");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;

$pag = new AMTDiario();


$pag->add("Esta sera a pagina para pesquisar outros diarios.");

echo $pag;

?>