<?
include("../../config.inc.php");

$pag = new CMHTMLPage;

$pag->requires("lib.js", CMHTMLObj::MEDIA_JS);

$script  = "function sendImageSrc(src) {\n ";
$script .= "  parent.document.getElementById('f_url').value = src;\n";
$script .= "  parent.onPreview();\n";
$script .= " }\n";

$script .= "function showHide(id) {\n";
$script .= " div = AM_getElement(id);\n";
$script .= " if(div.style.display=='none') div.style.display = 'block';\n";
$script .= " else div.style.display = 'none';";
$script .= "}\n";
$pag->addScript($script);

switch($_REQUEST['frm_upload_type']) {

  case "user":
    $dir = "$_CMAPP[path]/ambiente/paginas/users/user_".$_SESSION['user']->codeUser;
    break;
 case "project":
   $dir = "$_CMAPP[path]/ambiente/paginas/projetos/projeto_".$_REQUEST['codeProjeto'];
   break;
 case "course":
   $dir = "";
   break;
}


$images = AMUpload::getImagesFromFolder($dir);
//note($images);die();

$cont = 0;

function parseImages($list,$pos=5,$name="") {
  global $pag, $_CMAPP, $language;
  
  if($name == "") $pag->add("<div id='$name' style='display: block;'>");
  else $pag->add("<div id='$name' style='margin-left: $pos; display: none;'>");
  $cont="";
  foreach($list as $k=>$item) {
    
    if(is_int($k)) {
      
      $urlImage = "$_CMAPP[media_url]/thumb.php?frm_image=$item[src]";
      
      if($cont % 2 == 0) $bgcolor = "#EFEFEF";
      else $bgcolor = "#DFDFDF";
      
      $pag->add("<div id='$item[filename]' style='margin-left: ".$pos."px; background-color: $bgcolor;'>");
      $pag->add("<a onClick=\"sendImageSrc('$item[url]');\">");
      $pag->add("<img src='$urlImage'></a>&nbsp;");
      $pag->add("$item[filename]</div>");
      $cont ++;
    }else {
      $pag->add("<img src='$_CMAPP[images_url]/ico_arq_pasta.gif' onClick=\"showHide('$k')\">");
      $pag->add("<a onClick=\"showHide('$k');\" >$k</a><br>");
      $alert = "<i><font size='2'>Vazio</font></i>";
      if(!empty($item)) parseImages($item,($pos+5),$k);
      else $pag->add("<div id='$k' style='display: none; margin-left: ".$pos."px;'>$alert</div>");
    }
  }
  $pag->add("</div>");
}

parseImages($images);


echo $pag;
die();

?>