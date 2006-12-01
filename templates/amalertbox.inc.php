<?
/**
 * Show any message to user
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMAbstractBox
 */

Class AMAlertBox extends AMAbstractBox {
  
  protected $theme;
  protected $icon = true;
  protected $contents = array();
  protected $class;
  
  const ALERT = "01";
  const DIALOG = "04";
  const MESSAGE = "02";
  const ERROR = "03";

  public function  __construct($theme,$value="") {
    parent::__construct();

    $this->requires('alertbox.css',CMHTMLObj::MEDIA_CSS);

    $this->theme = 'box_alert_'.$theme;
    if(!empty($value)) $this->contents[] = $value;

    switch($theme) {
    case self::ALERT:
      $this->table_id = "box_alert";
      break;
    case self::DIALOG:
      $this->table_id = "box_dialog";
      $this->icon = false;
      break;
    case self::MESSAGE:
      $this->table_id = "box_message";
      $this->class = "message";
      break;
    case self::ERROR:
      $this->table_id = "box_error";
      $this->class = "error";
      break;
    }
  }


  public function add($item) {
    $this->contents[] = $item;
  }


  public function __toString() {
    global $_CMAPP;

    $url = $_CMAPP['images_url'].'/'.$this->theme;

    parent::add('<div id="'.$this->id.'" class="alert-box">');
    parent::add("<table id='$this->table_id' class='alert_box_table'>");
    parent::add('<tr>');
    parent::add('<td width="10"><img src="'.$url.'/box_alert_es.gif" width="10" height="10" border="0"></td>');
    parent::add('<td><img src="'.$url.'/dot.gif" width="1" height="1" border="0"></td>');
    parent::add('<td width="10"><img src="'.$url.'/box_alert_ds.gif" width="10" height="10" border="0"></td>');
    parent::add('</tr>');
    parent::add('<tr> ');
    parent::add('<td><img src="'.$url.'/dot.gif" width="1" height="1" border="0"></td>');
    parent::add('<td><div id="msg_alert" class="' . $this->class . '">');
    if($this->icon) parent::add('<img src="'.$url.'/ico_alert.gif"> '); 
    
    parent::add($this->contents);

    parent::add('</div></td>');
    parent::add('<td><img src="'.$url.'/dot.gif" width="1" height="1" border="0"></td>');
    parent::add('</tr>');
    parent::add('<tr> ');
    parent::add('<td><img src="'.$url.'/box_alert_ei.gif" width="10" height="10" border="0"></td>');
    parent::add('<td><img src="'.$url.'/dot.gif" width="1" height="1" border="0"></td>');
    parent::add('<td><img src="'.$url.'/box_alert_di.gif" width="10" height="10" border="0"></td>');
    parent::add('</tr>');
    parent::add('</table>');
    parent::add('</div>');

    return parent::__toString();
  }
    
}

?>