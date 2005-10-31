<?


class AMTwoColsLayout extends CMHTMLObj {

  const LEFT=0;
  const RIGHT=1;


  private $left_column = array();
  private $right_column = array();
  protected $width="95%";

  public function add($obj, $col) {
    
    switch($col) {
    case self::LEFT:
      $this->left_column[] = $obj;
      break;
    case self::RIGHT:
       $this->right_column[] = $obj;
      break;
    }
  }
  
  public function setWidth($value) {
    $this->width = $value;
  }

  public function getWidth() {
    return $this->width;
  }

  public function __toString() {
    global $_CMAPP;

    parent::add("<!--main body -->");
    parent::add("<table cellpadding=0 cellspacing=0 border=0 width=$this->width>");
    parent::add("<tr><td colspan=3><img src=".$_CMAPP['images_url']."/dot.gif width=20 height=20></td></tr>");
 
    
    parent::add("<!-- coluna da esquerda -->");
    parent::add("<td valign=top width='50%'>");

    parent::add($this->left_column);

    parent::add("</td>");
    parent::add("<!-- final coluna da esquerda -->");
    
    parent::add("<td width=20><img src=".$_CMAPP['images_url']."/dot.gif width=20 height=1 border=0></td>");
    
    parent::add("<!-- coluna da direita -->");
    parent::add("<td valign=top width='50%'>");
    parent::add($this->right_column);

    parent::add("</td></tr>");
    parent::add("<!-- final coluna da direita -->");
    
    parent::add("</td></tr></table>");
    parent::add("<!-- final main body -->");
    

    return parent::__toString();
  }

}


?>