<?
$_CMAPP['notrestricted']=true;
include('config.inc.php');

$_language = $_CMAPP['i18n']->getTranslationArray("invalid_login");

$pag = new AMTCadastro();
$box = new AMColorBox("",AMColorBox::COLOR_BOX_BEGE);
$box->setWidth("90%");
$box->add("<p align=center><a class=\"cinza\" href=\"$_CMAPP[url]/index.php\">".$_language['back_to_start']."</a>");
$pag->add($box);
echo $pag;

?>