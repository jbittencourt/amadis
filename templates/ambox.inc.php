<?
/**
 * Base of box templates
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMAbstractBox
 */

class AMBox extends AMAbstractBox {

  const COLOR_WHITE = "box_12";

  protected $theme;
  protected $class;
  private $items;

  public function __construct($id,$theme='') {
    parent::__construct($id);
    $this->theme = $theme;

    $this->requires("box.css",CMHTMLObj::MEDIA_CSS);

    switch($theme) {
    case self::COLOR_WHITE:
      $this->class = "box_white";
      break;
    }
  }

  public function add($line) {
    $this->items[] = $line;
  }

  public function __toString() {
    global $_CMAPP;
    
    $injection = array(
    	'theme'=>$this->theme,
    	'class'=>$this->class,
    	'box_id'=>$this->id,
    	'previx'=>$prefix,
    	'box_content'=>implode("\n", $this->items)
    );
    

    
    parent::add(AMHTMLPage::loadView($injection, 'box'));
    return parent::__toString();     
  }
}
?>