<?
/**
 * Small project list, to show in AMADIS initial page
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMProject
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMTProjectsSmallList extends CMHTMLObj {

  protected $items;

  public function __construct(CMContainer $items) {
    parent::__construct();
    $this->items = $items;
  }


  public function __toString() {
    global $_CMAPP,$_language;

    parent::add("<table class='small_list'>");
    if($this->items->__hasItems()) {
      foreach($this->items as $proj) {
	parent::add("<tr>");
	parent::add("<td>");
	$img = AMProjectImage::getThumb($proj,true);
	parent::add($img->getView());
	parent::add("<td>");
	$text = substr($proj->description,0,50);
	if($text!=$proj->description) {
	  $text.="...";
	}
	parent::add("<a href='$_CMAPP[services_url]/projetos/projeto.php?frm_codProjeto=$proj->codeProject'>$proj->title</a> - $text");
	parent::add("<tr><td colspan=2><img src='$_CMAPP[images_url]/dot.gif' height='10'>");
      }
    }
    else {
      parent::add("<tr>");
      parent::add("<td>".$_language['no_project_found']);
    }
    parent::add("</table>");

    return parent::__toString();
  }

  

}

?>
