<?php

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

$box1 = new AMBAdminStates;

$box2 = new AMBAdminCities;

$pag->add($box1);
$pag->add($box2);
echo $pag;
