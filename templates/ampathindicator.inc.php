<?

/**
 * Path indicator, to register a path in an action
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */

class AMPathIndicator extends CMHTMLObj {

  private $elements;
  private $state;
  private $align="center";

  public function __construct($elements) {
    parent::__construct();
    if(empty($elements))
      Throw new AMException("You must define an array of elements to the Path indicator, but the variable is empty.");
    if(!is_array($elements))
      Throw new AMException("You must define an array of elements as paramenter to the Path indicator.");

    $this->elements = $elements;
  }

  public function setState($state) {
    $this->state = $state;
  }

  public function getState() {
    return $state;
  }

  public function setAlign($value) {
    $this->align = $value;
  }

  public function __toString() {
    global $_CMAPP;

    if(empty($this->state)) {
      $temp = array_keys($this->elements);
      $this->state = $temp[0];
    }   


    $temp = $this->elements;
    foreach($temp as $k=>$item) {
      $temp[$k] = "<td class=\"progress_normal\">$item</td>";
    }
    $temp[$this->state] = "<td class=\"progress_realcado\">".$this->elements[$this->state]."</td>";

    $img = "<td><img src=\"".$_CMAPP['images_url']."/arrow.gif\"> <td>";
    parent::add("<!-- Navigation path indicator -->");
    parent::add("<table align=\"$this->align\" border=0 cellspacing=1 cellpadding=0><tr valign=center>".implode($img,$temp)."</tr></table>");

    return parent::__toString();
  }

}


?>