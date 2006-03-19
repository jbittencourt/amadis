<?
$_CMAPP[notrestricted]=1;
include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("communities");

$pag = new AMTCommunities;

$box = new AMTwoColsLayout;

//coluna da esquerda
//$box->add("Sample", AMTwoColsLayout::LEFT);
$box->add(new AMBCommunitiesSearch, AMTwoColsLayout::LEFT);
$box->add("<br><br>",AMTwoColsLayout::LEFT);
$box->add("<a href=\"$_CMAPP[services_url]/communities/create.php\"><img border=\"0\" src=\"$_CMAPP[imlang_url]/img_nova_comunidade.gif\"></a>", AMTwoColsLayout::LEFT);

//there is a logged user
if(!empty($_SESSION[user])) {
  $box->add(new AMBMyCommunities, AMTwoColsLayout::RIGHT);
  $box->add("<br><br>",AMTwoColsLayout::RIGHT);
}

$box->add(new AMBCommunitiesBigger, AMTwoColsLayout::RIGHT);

$pag->add($box);

$pag->add("<br><table celspacing=\"0\" celspadding=\"0\" border=\"0\" width=\"500\"><tr><td>");
$pag->add(new AMDotLine(500));
$pag->add("</td></tr>");
$pag->add("<tr><td><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"12\">");
$pag->add(new AMBCommunitiesNews);
$pag->add("</td></tr></table>");
 
echo $pag;

?>