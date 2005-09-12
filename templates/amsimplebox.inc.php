<?

abstract class AMSimpleBox extends CMHTMLObj {
  
  private $title;
  
  public function __construct($title="", $width="240",$align="left"){
    parent::__construct();
    $this->title = $title;
  }

  public function add($item) {
    $this->stack[] = $item;
  }

  public function __toString(){
    global $_CMAPP;

    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">");
    parent::add("<tbody>");
    parent::add("<tr>");
    parent::add("<td class=\"textoint\">");
    parent::add("<img src=\"".$this->title."\" border=\"0\"><br>");
    parent::add("<!--Begin parse itens-->");

    if(!empty($this->stack)) {
      foreach($this->stack as $item) {
	parent::add($item);
      }
    }

    parent::add("<!--End parse itens-->");
    parent::add("</td>");
    parent::add("</tr>");
    parent::add("</tbody>");
    parent::add("</table>");

    return parent::__toString();

  }
}

?>