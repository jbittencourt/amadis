<?
$no_redirect_on_logon_failure  = 1;

include_once("../../config.inc.php");
include_once("$pathuserlib/amoficina.inc.php");
include_once("$pathuserlib/amoficinacoordenador.inc.php");
include_once("$pathtemplates/amtoficinas.inc.php");
include_once("$pathtemplates/ambox.inc.php");

include_once("$rdpath/interface/rdjswindow.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$rdpath/interface/wmime.inc.php");

$ui = new RDui("biblioteca");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AMTOficinas();  
$pag->add ("<br>");

$oficina = new AMOficina($_REQUEST[codOficina]);
//$coords = $_SESSION[usuario]->listaOficinasCoordenador();

if (!empty($_REQUEST[acao])) {
  switch ($_REQUEST[acao]) {
  case "A_salva_doc":

    if(!$arquivo->novo) {
      $doc = new AMBibliotecaDoc();
      $doc->codUser = $_SESSION[usuario]->codUser;
      $doc->desTitulo = $_REQUEST[frm_desTitulo];
      $doc->codOficina = $_REQUEST[codOficina];
      $doc->flaRestrito = $_REQUEST[frm_flaRestrito];
      $doc->tempo = time();
      
      $doc->file = $_FILES[frm_file];
	 
      if($oficina->eCoordenador($_SESSION[usuario]->codUser)) {
	$doc->flaAceito = 1;
      };

      $doc->salva();
    }

    
    $mens[] = $lang[doc_salvo_sucesso];
    break;
  case "A_novo_doc":
      $tab = new AMBox();
      $tab->setTitle($lang[novo_doc]);

      $ocultos = array("codDoc","codUser","codOficina","codArquivo","flaAceito","tempo");

      $form = new WSmartForm("AMBibliotecaDoc","novo_doc","biblioteca.php?acao=A_salva_doc",$ocultos);
      $form->setDesign(WFORMEL_DESIGN_OVER);

      $file = new WFile("frm_file");
      $form->addComponent("file",$file);

      $ho = new WHidden("codOficina",$oficina->codOficina);
      $form->addComponent("codOficina",$ho);

      $tab->add($form);

      $pag->add("<br>");
      $pag->add($tab);
      $pag->imprime();
      die();

      break;
  }
}

$menu[$lang[voltar]] = "$url/ferramentas/oficinas/oficina.php?frm_codOficina=".$oficina->codOficina;
$menu[$lang[novo_doc]] = "biblioteca.php?acao=A_novo_doc&codOficina=".$oficina->codOficina;

$pag->setSubMenu ($menu);

if(!empty($mens)) {
  foreach($mens as $men) 
    $pag->add("<br><div class=\"alert\">$men</div>");
}


$tab = new AMBox(2);
$tab->setWidth(0,"70%");
$tab->setWidth(1,"30%");
$tab->setTitle($lang[biblioteca]);

$docs = $_SESSION[biblioteca]->listaDocumentos();

if(!empty($docs->records)) {
  foreach($docs->records as $doc) {
    $itens = array();
    $itens[] = $doc->desTitulo;

    $link = "<a class=\"fontgray\" href=\"$url/ferramentas/arquivos/download.php?codArquivo=$doc->codArquivo\">[$lang[download]]</a>&nbsp;";

    $mime = new WMime($doc->desTipoMime);
    if($mime->hasDriver()) {
      $win = new RDJSWindow("$url/ferramentas/arquivos/conv.php?type=html&codArquivo=".$doc->codArquivo,"",600,600);
      $link.= "<a class=\"fontgray\" href=\"#\" onClick=\"".$win->getScript()."\">[$lang[ver]]</a>";
    }

    $itens[] = $link;

    $tab->addRow($itens);
  }
}


$pag->add($tab);
$pag->imprime();



?>
