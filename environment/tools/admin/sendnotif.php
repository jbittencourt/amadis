<?
include_once("../../config.inc.php");
include "cmwebservice/cmwemail/cmwsimplemail.inc.php";

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

$box = new AMBAdminSendNotif;

$box1 = new AMTwoColsLayout;

$box1->add($box, AMTwoColsLayout::LEFT);

$pag->add($box1);

echo $pag; 


?>