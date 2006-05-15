<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjLibrary extends AMColorBox {

  private $proj;
  private $library;
  private $limit = 5; //this var is to set how many results must appears in the box
  
  
  public function __construct($proj) {
    global $_CMAPP;
    $this->requires("library.css",CMHTMLObj::MEDIA_CSS);
    $this->proj = $proj;
    $this->library = $proj->getLibrary();

    parent::__construct($_CMAPP['imlang_url']."/box_biblio_projeto_tit.gif",self::COLOR_BOX_PURPLE);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    $base_link = "../library/biblioteca.php?frm_type=project&frm_codeProjeto=".$this->proj->codeProject;
    

    $ple = new AMLibrary();
    $ple->setLibrary($this->library);
    $list = $ple->getLastFiles($this->limit);    

    if(!$list->__hasItems()){
      parent::add($_language['no_files']);
    }
    else {
      parent::add("<font class='blt_subtitulo'>$_language[last_five]</font>");
      parent::add("<table>");
      foreach($list as $item) {
	$mimeType = explode("/",$item->tipoMime);
	switch($mimeType[1]){
	case "pdf":
	  $icon = "/images/icon_pdf.gif";
	  break;
	case "msword":
	  $icon = "/images/icon_img01.gif";
	  break;
	case "jpeg":
	  $icon = "/images/icon_img03.gif";
	  break;
	case "png":
	  $icon = "/images/icon_img02.gif";
	  break;
	case "gif":
	  $icon = "/images/icon_img02.gif";
	  break;
	case "x-shockwave-flash":
	  $icon = "/images/icon_swf.gif";
	  break;
	case "html":
	  $icon = "/images/icon_html.gif";
	  break;
	case "zip":
	  $icon = "/images/icon_zip.gif";
	  break;
	case "vnd.sun.xml.impress":
	  $icon = "/images/icon_apresenta.gif";
	  break;
	case "vnd.sun.xml.writer":
	  $icon = "/images/icon_img01.gif";
	  break;    
	case "vnd.ms-powerpoint":
	  $icon = "/images/icon_apresenta.gif";
	  break;
	case "plain":
	  $icon = "/images/icon_apresenta.gif";
	  break;
	default:
	  if($mimeType[0] == "audio"){
	    $icon = "/images/icon_som.gif";
	  }
	  elseif($mimeType[0] == "video"){
	    $icon = "/images/icon_video.gif";
	  }
	  else{
	    $icon = "/images/icon_outro.gif";
	  }	      
	  break;
	}

	parent::add("<tr><td class='blt_box_p'><a href='$_CMAPP[services_url]/library/biblioteca.php?frm_type=projeto&frm_codeProjeto=$_REQUEST[frm_codProjeto]&opcao=download&codeArquivo=$item->codeArquivo'><img src='$_CMAPP[media_url]$icon'></a></td>");
	parent::add("<td class='blt_box_p'>&raquo; $item->nome </td>");
	$shared = $ple->isShared($item->codeArquivo);
	if($shared == "true"){
	  $sh = "<td>(".$_language['isShared'].")</td>";
	  parent::add($sh);
	}
	parent::add("<td class='blt_box_p'> - ".date("d/m/Y",$item->tempo)." </td></tr>");
      }
      parent::add("</table>");
    }
      parent::add("<br><a href='$base_link'><font class='blt_subtitulo'>&raquo; $_language[view_all_files]</font></a>");    
    
    return parent::__toString();
  }
}
?>