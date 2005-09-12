<?

include("../../config.inc.php");
include_once("$rdpath/upload/rddbupload.inc.php");

if(empty($_REQUEST[codArquivo])) {
  die();
}
  
$arq = new RDDBUpload($_REQUEST[codArquivo]);

if(!$arq->novo) {
  header('Content-Type: application/octet-stream');
  // Send content-length HTTP header
  header('Content-Length: '.$arq->desTamanho);
  // Send content-disposition with save file name HTTP header
  header('Content-Disposition: attachment; filename="'.$arq->desNome.'"'); 
  if(!empty($arq->desDados)) {
    echo $arq->desDados;
  }
}

?> 