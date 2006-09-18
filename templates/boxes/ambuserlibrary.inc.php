<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBUserLibrary extends AMColorBox {

  private $user;
  private $library;
  private $limit; //this var is to set how many results must appears in the box
  private $show_link; //if this var is set 1, show the link to 'show all shared files'
  
  public function __construct($user, $limit, $show) {
    global $_CMAPP;
    $this->requires("library.css",CMHTMLObj::MEDIA_CSS);
    $this->user = $user;
    $a = new AMUserLibraryEntry($user->codeUser);
    $this->library = $a->getLibrary($user->codeUser);
    $this->limit = $limit;
    $this->show_link = $show;
    parent::__construct($_CMAPP['imlang_url']."/box_wfarquivos_tit.gif",self::COLOR_BOX_PURPLE);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    $base_link = "../library/library.php?frm_codeUser=".$this->user->codeUser;


    $ple = new AMLibrary();
    $ple->setLibrary($this->library);
    $list = $ple->listSharedFiles($this->limit);    

    //note($list);
    if(!$list->__hasItems()){
      parent::add($_language['no_files']);
    }
    else {
      parent::add("<table>");
      foreach($list as $item) {
	$mimeType = explode("/",$item->mimetype);
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

	parent::add("<tr><td class='blt_box_p'><a href='$_CMAPP[services_url]/library/biblioteca.php?frm_type=projeto&frm_codeProjeto=$_REQUEST[frm_codProjeto]&opcao=download&codeArquivo=$item->codeFile'><img src='$_CMAPP[media_url]$icon'></a></td>");
	parent::add("<td class='blt_box_p'>&raquo;<a href='$_CMAPP[services_url]/library/biblioteca.php?frm_type=projeto&frm_codeProjeto=$_REQUEST[frm_codProjeto]&opcao=download&codeArquivo=$item->codeFile'>$item->name</a></td>");
	parent::add("<td class='blt_box_p'>( ".date("d/m/Y",$item->time)." )</td></tr>");
      }
      parent::add("</table>");
      if($this->show_link == 1)
	parent::add("<br><a href='$base_link'><font class='blt_subtitulo'>&raquo; $_language[list_all_files]</font></a>");
    }
          
    
    return parent::__toString();
  }
}
?>