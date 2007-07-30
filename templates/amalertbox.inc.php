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

class AMAlertBox extends AMAbstractBox {
  
  	protected $theme;
  	protected $icon = true;
  	protected $contents = array();
  	protected $class;
  
  	const ALERT = "01";
  	const DIALOG = "04";
  	const MESSAGE = "02";
  	const ERROR = "03";

  	public function  __construct($theme,$value="") 
  	{
    	parent::__construct();

    	$this->requires('alertbox.css',CMHTMLObj::MEDIA_CSS);

    	$this->theme = 'box_alert_'.$theme;
    	if(!empty($value)) $this->contents[] = $value;

    	switch($theme) {
    		case self::ALERT:
      			$this->id = "box-alert";
      			$this->class = 'alert';
      			break;
    		case self::DIALOG:
      			$this->id = "box-dialog";
      			$this->icon = false;
      			break;
    		case self::MESSAGE:
      			$this->id = "box-message";
      			$this->class = "message";
      			break;
    		case self::ERROR:
      			$this->id = "box-error";
      			$this->class = "error";
      			break;
    	}
  	}


  	public function add($item) 
  	{
    	$this->contents[] = $item;
  	}


  	public function __toString() 
  	{
    	global $_CMAPP;

	    $url = $_CMAPP['images_url'].'/'.$this->theme;
		$this->add('<img src="'.$_CMAPP['images_url'].'/close.gif" class="closeButton" onclick="$(\''.$this->id.'\').remove();" alt="" />');
	    $injection = array(
	    	'box_class'=>$this->class,
	    	'box_id'=>$this->id,
	    	'box_title'=>'<img src="'.$url.'/ico_alert.gif" alt="" class="alert-ico" />',
	    	'box_content'=>implode("\n", $this->contents)
	    );
    	parent::add(AMHTMLPage::loadView($injection, 'box'));
    	return parent::__toString();
	}
    
}