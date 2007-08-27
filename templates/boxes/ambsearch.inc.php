<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBSearch extends AMColorBox  {

  	private static $_inicialized = false;
  	private $search;
  	public $action;

  	public function __construct($action,$title,$theme) {
    	parent::__construct($title,$theme);
    	$this->requires("search.js",CMHTMLObj::MEDIA_JS);
    	$this->action = $action;
  	}
  

  	public function __toString() {
    	global $_language,$_CMAPP;

    	if(!self::$_inicialized) {
      		parent::addPageEnd(CMHTMLObj::getScript(" message_empty_search = '$_language[empty_search]'; "));
      		self::$_inicialized = true;
    	}


    	$form  = '<form action="'.$this->action.'" method="post" onsubmit="return Search_validateForm(this.elements[\'frm_search\'].value);">';
    	$form .= '<div><input type="hidden" name="search_action" value="listing" />';
    	$form .= '<input type="hidden" name="frm_action" value="search_result" />';
    	$form .= $t.'<input type="text" name="frm_search" id="frm_search" />';
        
    	$form .= AMMain::getSearchButton();
    	$form .= '<input type="hidden" name="list_action" value="search_communities" />';
    
    	$form .= '</div></form>';
    
    	parent::add($form);
    
    	return parent::__toString();
  	}
}