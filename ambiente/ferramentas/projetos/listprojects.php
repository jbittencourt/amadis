<?
$_CMAPP[notrestricted] = 1;

include("../../config.inc.php");

include($_CMAPP[path]."/templates/amtprojeto.inc.php");
include($_CMAPP[path]."/templates/amsimplebox.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("projects");

$pag = new AMTProjeto;

$pag->add(new AMBProjectList);

echo $pag;

?>