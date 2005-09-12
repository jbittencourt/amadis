<?

include("../../config.inc.php");
include_once("$rdpath/upload/rddbupload.inc.php");
include_once("$rdpath/interface/wmime.inc.php");

$pag = new RDPagina();


if(empty($_REQUEST[codArquivo])) {
  die();
}
  

$arq = new RDDBUpload($_REQUEST[codArquivo]);

if(!$arq->novo) {

  switch($_REQUEST[type]) {
  case "html":
    $doc = new WMime($arq->desTipoMime);
    $doc->setData($arq->desDados);
    $pag->add($doc);
    break;
  }

}

$pag->imprime();

?> 