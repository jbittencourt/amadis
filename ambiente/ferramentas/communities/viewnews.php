<?
$_CMAPP['notrestricted'] = 1;

include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("communities");

$pag = new AMTCommunities;

$box = new AMBCommunityList;

echo $pag;

?>