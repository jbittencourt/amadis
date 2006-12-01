<?
include("../../config.inc.php");

$pag = new CMHTMLPage;

global $_conf;

$pag->add("<table width='150' height='100' cellpadding='1' cellspacing='1' border='1' align='center'>");
$pag->add("<tr>");
$count=0;
foreach($_conf->app->interface->smilies->smile as $smile){
  $count++;
  if($count == 11){
    $pag->add("</tr><tr>");
    $count=1;
  }
  $pag->add("<td width='28' height='28'><img id=".$smile['key']." src='../images/smilies/".$smile['image']."'></td>");  
}
$pag->add("</tr></table>");


echo $pag;
?>