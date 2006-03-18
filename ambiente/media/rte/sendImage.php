<?
include("../../config.inc.php");

$pag = new CMHTMLPage;

$_language = $_CMAPP[i18n]->getTranslationArray("library");

$pag->requires("rte_ins_image.css", CMHtmlObj::MEDIA_CSS);

$pag->addStyle("Body { margin: 5px; background: none; }");

if(isset($_REQUEST['action']) && $_REQUEST['action'] == "A_sendImage") {

  $a = new AMUserLibraryEntry($_SESSION[user]->codeUser);
  $a->libraryExist();
  $lib = $a->getLibrary($_SESSION['user']->codeUser);
  

  $tipo = explode("/", $_FILES[$formName]['type']);
  $file_type =  explode(".",$_FILES[$formName]['name']);
  
  $filelib = new AMLibraryFiles;
  $file = new AMImage;
  if(isset($_REQUEST[codeArquivo])) {
    $file->codeArquivo = $_REQUEST[codeArquivo];
    $file->load();
    $file->state = CMObj::STATE_DIRTY;
  }

  //preenche os capos do arquivo
  $file->loadFileFromRequest("file");
  $file->tempo = time();
  
  if($_FILES['file']['tmp_name'] == "")
    return false;
  
  $file->dados  = implode("",file($_FILES['file']['tmp_name']));
  
  try{

    $file->save();
    $codeArquivo = $file->codeArquivo;
    if(!isset($_REQUEST[codeArquivo])) {
      $filelib->libraryCode = $lib;
      $filelib->filesCode = $file->codeArquivo;
      $filelib->time = time();
      $filelib->save();
    }

    $thumb = new AMLibraryThumb;
    $thumb->codeArquivo = $file->codeArquivo;
    $thumb->load();
    $url = $thumb->thumb->getThumbUrl();
    $meta = explode("|", $file->metaDados);
    
    $click = "parent.AddImage('../../media/thumb.php?frm_image=$file->codeArquivo&action=library',document.getElementById('legend').value);";
    
    $out[] = "<div class='item' style=\"background-image: url('$url');\">";
    $out[] = "  <div>";
    $out[] = "    $file->nome<br>$meta[0]x$meta[1] px / {$meta[2]}KB";
    $out[] = "    <p><a onClick=\"var leg = document.getElementById('legenda'); if(leg.style.display =='block') leg.style.display='none'; else leg.style.display='block';\">&raquo;Legenda</a></p>";
    $out[] = "  </div>";
    $out[] = "  <div class='buttonOK'><button onClick=\"$click\"><img src='../images/buttonOK.gif'></button></div>";
    $out[] = "  <div id='legenda' style='display:none;'>";
    $out[] = "    <input type='text' size='35' name='legend' id='legend'>";
    $out[] = "  </div>";
    $out[] = "</div>";
    
    $pag->add(implode("\n", $out));

  } catch (CMException $e) {
    echo $e->getMessage();
    $pag->add($_language[error_send_file]);
    notelastquery();
  }

}

$script = array();
$script[] = "function checkFile(file, form) {";
$script[] = "  var er = /(".implode('|', AMImage::getValidImageExtensions()).")/i;";
$script[] = "  if(er.test(file.value)) form.submit();";
$script[] = "  else {";
$script[] = "    alert('Os tipos de imagens permitidas sao: ".implode(",", AMImage::getValidImageExtensions())."');";
$script[] = "    return false;";
$script[] = "  }";
$script[] = "}";

$pag->add(CMHTMLObj::getScript(implode("\n", $script)));

$pag->add("<form action='#' enctype='multipart/form-data' name='sendImageForm' method='post'>");
$pag->add("  <input type='file' name='file' id='file' size='33' onChange='checkFile(this, document.sendImageForm);'>");
$pag->add("  <input type='hidden' name='action' value='A_sendImage'>");
if(isset($codeArquivo)) $pag->add("<input type='hidden' name='codeArquivo' value='$codeArquivo'>");
$pag->add("</form>");

echo $pag;