<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBPeopleSearchUsers extends AMPageBox implements CMActionListener {

	private $itens = array();
  	protected $form;
  	private $parent;

  	public function __construct() 
  	{
    	parent::__construct(10);
  	}

  	protected function addForm($form) {
    	$this->form = $form;
  	}

  	public function doAction() {
    	global $_CMAPP, $_CMDEVEL, $_language;
    	if(!isset($_REQUEST['search_action'])) $_REQUEST['search_action']='';
   		switch($_REQUEST['search_action']) {

   			default :
				$box = new AMBSearch("$_SERVER[PHP_SELF]","$_CMAPP[imlang_url]/box_pessoas_localizador.gif", AMColorBox::COLOR_BOX_ROSA);
	      		parent::add($box);

    	  		break;
    
    		case "listing" :
      
      			$result = $_SESSION['environment']->searchUsers(trim($_REQUEST['frm_search']), $this->init, $this->numHitsFP);
      
      			$this->numItems = $result['count'];
      			$this->itens = $result[0];

	      		$box = new AMUserList($this->itens,"$_language[search_users]",AMUserList::PEOPLE);
    	  		if(!isset($_REQUEST['frm_action'])) $_REQUEST['frm_action']='';
      			$this->addRequestVars("frm_action=$_REQUEST[frm_action]&search_action=$_REQUEST[search_action]&frm_search=$_REQUEST[frm_search]");
            
      			parent::add($box);    
      			$this->parent = true;
      			break;
    	}
  	}

  	public function __toString() {
    	if($this->parent) return parent::__toString();
    	else return CMHTMLObj::__toString();
  	}
}