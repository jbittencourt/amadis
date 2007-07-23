<?

/**
 * A simple and litle box =P
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
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

    parent::add('<div class="simple-box">');
    parent::add('<img src="'.$this->title.'" alt="" /><br />');
    parent::add('<!--Begin parse itens-->');

    if(!empty($this->stack)) {
    	foreach($this->stack as $item) {
			parent::add($item);
      	}
    }

    parent::add('<!--End parse itens-->');
    parent::add('</div>');

    return parent::__toString();
  }
}
?>