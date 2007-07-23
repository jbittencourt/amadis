<?php
$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("people");
$pag = new AMTPeople;

if(!isset($_REQUEST['frm_action'])) $_REQUEST['frm_action']='';
switch($_REQUEST['frm_action']) {
 
 default:
   $box = new AMTwoColsLayout;
   //coluna da esquerda
   $box->add(new AMBPeopleSearchUsers, AMTwoColsLayout::LEFT);
   $box->add("<br /><br />",AMTwoColsLayout::LEFT);
   $box->add(new AMBPeopleLastUsersLogeds, AMTwoColsLayout::LEFT);
    
   $box->add(new AMBPeopleLastPagesModified, AMTwoColsLayout::RIGHT);
   $box->add(new AMBPeopleLastDiaryPosts, AMTwoColsLayout::RIGHT);
   
   $pag->add($box);
   break;

 case "search_result":
   $pag->add(new AMBPeopleSearchUsers);
   break;
 
}

echo $pag;