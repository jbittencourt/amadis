<?
/**
 * Colorized boxes =)
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */
class AMColorBox extends AMAbstractBox {

  	protected $theme;
  	protected $class;
  	protected $title;
  	/**
   	 * Force the title to be interpreted as a string and not as a img URL
   	 **/
  	protected $forceTitleAsString = false;  
  
  	const COLOR_BOX_BEGE        = "box_02";
  	const COLOR_BOX_BLUE        = "box_01";
  	const COLOR_BOX_BLUA        = "box_03";
  	const COLOR_BOX_NONE        = "box_01";
  	const COLOR_BOX_GREEN       = "box_04";
  	const COLOR_BOX_ROSA        = "box_05";
  	const COLOR_BOX_LGREEN      = "box_06";
  	const COLOR_BOX_GREEN2      = "box_07";
  	const COLOR_BOX_BLUEB       = "box_08";
  	const COLOR_BOX_PURPLE      = "box_09";
  	const COLOR_BOX_DARKPURPLE  = "box_10";
  	const COLOR_BOX_YELLOW      = "box_11";
  	const COLOR_BOX_YELLOWB     = "box_13";
  	const COLOR_BOX_PINK        = "box_14";
  	const COLOR_BOX_TURQUESA    = "box_15";
  	const COLOR_BOX_INNERBLOG   = "box_innerblog";

  	public function __construct($title,$theme)
  	{
    	parent::__construct("240","left");

    	$this->requires("colorbox.css",CMHTMLObj::MEDIA_CSS);
    
    	$this->theme = $theme."/".$theme;

    	$this->title = $title;

    	switch ($theme) {
    		case self::COLOR_BOX_TURQUESA :
      			$this->class = "box-turquesa";
      			break;
    		case self::COLOR_BOX_BEGE:
      			$this->class = "box-bege";
      			break;
    		case self::COLOR_BOX_BLUE:
      			$this->class = "box-blue";
      			break;
    		case self::COLOR_BOX_BLUEB:
      			$this->class = "box-blueb";
      			break;
    		case self::COLOR_BOX_GREEN:
      			$this->class = "box-green";
      			break;
    		case self::COLOR_BOX_BLUA:
      			$this->class = "box-blua";
      			break;
    		case self::COLOR_BOX_ROSA:
      			$this->class = "box-rosa";
      			break;
    		case self::COLOR_BOX_LGREEN:
      			$this->class = "box-lgreen";
      			break;
    		case self::COLOR_BOX_GREEN2:
      			$this->class = "box-green2";
      			break;
    		case self::COLOR_BOX_PURPLE:
      			$this->class = "box-purple";
      			break;
    		case self::COLOR_BOX_DARKPURPLE:
      			$this->class = "box-darkpurple";
      			break;
    		case self::COLOR_BOX_YELLOW:
      			$this->class = "box-yellow";
      			break;
    		case self::COLOR_BOX_YELLOWB:
      			$this->class = "box-yellowb";
      			break;
    		case self::COLOR_BOX_PINK:
      			$this->class = "box-pink";
      			break;
    		case self::COLOR_BOX_INNERBLOG:
      			$this->class = "box-innerblog";
      			break;
    	}
  	}

	/**
   	 * Pode-se adicionar uma pilha de strings html em um array 
   	 * ou um unico string html para ser que irah para a tela
   	 **/
  	public function add($item) 
  	{
    	$this->contents[] = $item;
  	}

  	public function __toString() 
  	{
    	global $_CMAPP;
    
    	$injection = array(
    		'box_id'    => $this->name,
    		'box_width' => $this->width != 0 ? 'style="width:'.$this->width.'px;"' : '',
    		'box_class' => $this->class,
    		'box_theme' => $this->theme
   		);

   		if(!empty($this->title)) {
      		if(ereg("(jpg|gif|jpeg|png)", $this->title)) {
				$injection['box_title'] = '<img src="'.$this->title.'" alt="" /><br />';
      		} else $injection['box_title'] = '<span class="color-box box-titles">'.$this->title.'</span><br />'; 
    	} 

  		//parse itens
    	if(!empty($this->contents)) {
			$injection['box_content'] = implode("\n", $this->contents);
    	}
    
    	parent::add(AMHTMLPage::loadView($injection, 'color_box'));
    
    	return parent::__toString();
  	}

}