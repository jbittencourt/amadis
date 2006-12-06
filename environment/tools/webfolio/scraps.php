<?php
$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");


$_language = $_CMAPP['i18n']->getTranslationArray("scraps");

$pag = new AMTWebfolio;

$pag->add(new AMBUserScraps);

echo $pag;