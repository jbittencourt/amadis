<?

/**
 * List members of communities
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Cristiano S. Basso <csbasso@lec.ufrgs.br>
 */

class AMBCommunityMembers extends AMSimpleBox {

  private $itens = array();
  private $community;

  public function __construct($community) {
    global $_CMAPP;

    parent::__construct("$_CMAPP[imlang_url]/img_integrantes_comunidade.gif");

    $this->community = $community;
    $g = $community->getGroup();
    $this->itens = $g->listActiveMembers();

  }

  public function __toString() {
    global $_CMAPP, $_language;
    

    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\"><br>");

    if($this->itens->__hasItems()) {
      foreach($this->itens as $item) {
	parent::add("&nbsp;&nbsp;&nbsp;&raquo;");
	$temp = new AMTUserInfo($item);
	$temp->setClass("cinza");
	parent::add($temp);
	parent::add("<br>");
      }
      parent::add("<a href=\"".$_CMAPP[services_url]."/communities/members.php?frm_codeCommunity=".$this->community->code."\" class=\"cinza\">&nbsp;&nbsp;$_language[more_members]</a><br><br>");
    }
    else { 
      parent::add($_language[no_members]."<br><br>");
    }

    return parent::__toString();

  }
}

?>