<?
$_CMAPP[notrestricted] = True;
include("../../config.inc.php");
include($_CMDEVEL[path]."/cmwebservice/cmwsmartform.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("diary");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;


$pag = new AMTDiario();

$box = new AMDiaryList;
$items = AMAmbiente::listDiaries($box->getInitial(),$box->getFinal());
$box->init($items[data],$items[count]);
$pag->add($box);
echo $pag; 

?>