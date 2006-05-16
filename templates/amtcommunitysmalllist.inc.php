<?

/**
 * Smaall list of the communities showed in the initial page AMADIS
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMTCommunitySmallList extends CMHTMLObj {

  protected $items;

  public function __construct(CMContainer $items) {
    parent::__construct();
    $this->items = $items;
  }


  public function __toString() {
    global $_CMAPP;

    parent::add("<table class='small_list'>");
    if($this->items->__hasItems()) {
      foreach($this->items as $comm) {
	parent::add("<tr>");
	parent::add("<td>");
	$img = new AMCommunityThumb(true);
	$img->codeArquivo = $comm->image;
	$img->load();
	parent::add($img->getView());
	parent::add("<td>");
	$text = substr($comm->description,0,50);
	if($text!=$comm->description) {
	  $text.="...";
	}
	parent::add("<a href='$_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$comm->code'>$comm->name</a> - $text");
	parent::add("<tr><td colspan=2><img src='$_CMAPP[images_url]/dot.gif' height='10'>");
      }
    }
    parent::add("</table>");

    return parent::__toString();
  }

  

}

?>