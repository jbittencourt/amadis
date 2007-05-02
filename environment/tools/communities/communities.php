<?php
$_CMAPP['notrestricted']=1;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("communities");

$pag = new AMTCommunities;

$box = new AMTwoColsLayout;

if(!isset($_REQUEST['frm_action'])) $_REQUEST['frm_action']="";

switch($_REQUEST['frm_action']) {
	default:
   //coluna da esquerda

		$box->add($_language['community_intro']."<br>", AMTwoColsLayout::LEFT);
		$box->add("<br><br>", AMTwoColsLayout::LEFT);
   //---
		if($_SESSION['environment']->logged == 1){
			$box->add("<a href=\"$_CMAPP[services_url]/communities/create.php\"><img border=\"0\" src=\"$_CMAPP[imlang_url]/img_nova_comunidade.gif\"></a>", AMTwoColsLayout::LEFT);
			$box->add("<br><br>", AMTwoColsLayout::LEFT);
		}
		$box->add(new AMBCommunitiesBigger, AMTwoColsLayout::LEFT);

   /**
    *        Coluna da Dir
    **/

		$box->add(new AMBCommunitiesSearch, AMTwoColsLayout::RIGHT);
		if(!empty($_SESSION['user'])) {
			$box->add(new AMBMyCommunities, AMTwoColsLayout::RIGHT);
			$box->add("<br><br>",AMTwoColsLayout::RIGHT);
		}

		$pag->add($box);

		$pag->add("<br><table celspacing=\"0\" celspadding=\"0\" border=\"0\" width=\"500\"><tr><td>");
		$pag->add(new AMDotLine(500));
		$pag->add("</td></tr></table>");
		break;
	case "search_result":
		$pag->add(new AMBCommunitiesSearch);
		break;
}

echo $pag;

