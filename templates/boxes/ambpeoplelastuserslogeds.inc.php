<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBPeopleLastUsersLogeds extends AMSimpleBox implements CMActionListener {
  
  private $itens = array();
  
  public function __construct() {
    global $_CMAPP;

    parent::__construct($_CMAPP['imlang_url']."/img_pessoas_logadas.gif");
    
  }

  public function doAction() {
    $this->itens = $_SESSION['environment']->listLastUsersLogeds();

  }

  public function __toString() {
    global $_CMAPP, $_language;
    
    if($this->itens->__hasItems()) {
      foreach($this->itens as $item) {
	parent::add(new AMTUserInfo($item));
	parent::add("<br />");
      }
    } else parent::add("$_language[no_user_loggeds]");
    return parent::__toString();
  }


}

?>