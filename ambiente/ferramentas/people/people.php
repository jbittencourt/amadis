<?
$_CMAPP[notrestricted] = True;
include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("people");
$pag = new AMTPeople;

switch($_REQUEST[frm_action]) {
 
 default:
   $box = new AMTwoColsLayout;

   //coluna da esquerda
   //$box->add("Sample", AMTwoColsLayout::LEFT);
   $box->add(new AMBPeopleSearchUsers, AMTwoColsLayout::LEFT);
   $box->add("<br><br>",AMTwoColsLayout::LEFT);
   $box->add(new AMBPeopleLastUsersLogeds, AMTwoColsLayout::LEFT);
    
   //$box->add(new AMBSearch, AMTwoColsLayout::LEFT);
   
   //coluna da direita
   //$box->add("Sample", AMTwoColsLayout::RIGHT);
   
   $box->add(new AMBPeopleLastDiaryPosts, AMTwoColsLayout::RIGHT);
   
   $box->add(new AMBPeopleLastPagesModified, AMTwoColsLayout::RIGHT);
   
   $pag->add($box);
   break;

 case "search_result":
   $pag->add(new AMBPeopleSearchUsers);
   break;
 
}

echo $pag;

?>