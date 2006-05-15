<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMProjetoItensBox extends AMColorBox {
  
  private $link = array();
    
  public function __construct() {
    parent::__construct("media/images/box_itens_projeto.gif",self::COLOR_BOX_BLUA);
  }
    
  public function addLink($link, $url) {
    $this->links[] = array("url"=>$url,"link"=>$link);
  }

  public function __toString() {
    global $_CMAPP;
    if(!empty($this->links)) {
      parent::add(new AMLinksContainer($this->links));
    }

    return parent::__toString();
      
  }
}

?>
