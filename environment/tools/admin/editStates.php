<?php

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

$box = new AMTwoColsLayout;

$box1 = new AMBAdminStates;

$box2 = new AMBAdminCities;

$box->add($box1,AMTwoColsLayout::LEFT);
$box->add($box2,AMTwoColsLayout::RIGHT);

$pag->add($box);

echo $pag;