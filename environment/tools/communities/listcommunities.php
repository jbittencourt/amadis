<?php

$_CMAPP['notrestricted'] = 1;

include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("communities");

$pag = new AMTCommunities;

if(!isset($_REQUEST['list_action'])) $_REQUEST['list_action'] = "";

$pag->add(new AMBCommunityList);

/*if($_REQUEST['list_action'] == "A_list_news" || $_REQUEST['list_action'] == "A_list_projects"){
     $pag->add(new AMBCommunityList);
}else{
  $pag->add(new AMBCommunitiesList);
}
*/   
echo $pag;