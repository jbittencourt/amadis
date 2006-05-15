<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBMailMessages extends AMColorBox {
  
  public function __construct() {
    global $_CMAPP;
    
    parent::__construct("$_CMAPP[imlang_url]/box_caixa_correio.gif",AMColorBox::COLOR_BOX_BEGE);
    
  }

  public function __toString() { 
    global $_CMAPP,$_language;
    
    if(($numMessages = $_SESSION[user]->getNumberNotReadMessages()) > 0) {
      parent::add("&raquo; ");
      ($numMessages>1 ? parent::add("$numMessages $_language[new_message]") : 
       parent::add("$numMessages $_language[new_messages]"));
      parent::add("<br><a class=\"cinza\" href=\"\">$_language[send_new_message]");
      parent::add(new AMDotLine);
    } else {
      parent::add($_language[empty_inbox]);
      parent::add(new AMDotLine);
    }
    
    parent::add("<a class=\"blue\" href=\"$_CMAPP[services_url]/mail/mail.php\">&raquo; $_language[access_email]</a>");
    
    return parent::__toString();

  }
 
}

?>