<?php
include("../../config.inc.php");

$pag = new CMHTMLPage;

$pag->requires("forum.css", CMHTMLObj::MEDIA_CSS);
$pag->requires("forum.js", CMHTMLObj::MEDIA_JS);

$script  = "function sendImageSrc(src) {\n ";
$script .= "  parent.document.getElementById('imageURL').value = src;\n";
$script .= "  parent.document.getElementById('tipText').focus();";
$script .= " }\n";

$pag->add(CMHTMLObj::getScript($script));

$list = AMForum::loadImageLibrary($_REQUEST[frm_codeForum]);
$c = 0;
$pag->add("<table width='100%'>");
if($list->__hasItems()) {
  foreach($list as $item) {
    $image = new AMLibraryThumb;
    $image->setSize(133,100);
    $image->codeFile = $item->codeFile;
    try {
      $image->load();
      
      $url = $image->thumb->getThumbUrl();
      
      $class = ($c==0 ? $c++ : $c--);
      $pag->add("<tr class='InsertImage_line$class'><td>");
      
      $pag->add("<img style='cursor: pointer;' src='$url' onClick=\"sendImageSrc('../../media/thumb.php?frm_image=$item->codeFile&action=library')\">");
      
      $pag->add("</td><td>$item->nome<br />");
      $pag->add("</td>");
      
    }catch (CMException $e) {    
    }
    
  }
} else {
  $pag->add("<tr><td>Nao ha imagens na sua biblioteca</td></tr>");
}
$pag->add("</table>");

echo $pag;