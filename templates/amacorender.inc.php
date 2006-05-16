<?
/**
 * AMACORender
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMACO
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */

class AMACORender extends CMHTMLObj {

  protected $aco;
  
  public function __construct(CMACO $aco) {
    $this->requires('aco.css',CMHTMLObj::MEDIA_CSS);

    parent::__construct($this);
    $this->aco = $aco;
  }

  public function __toString() {
    global $_CMAPP;

    $_language = $_CMAPP[i18n]->getTranslationArray("acos");

    $privs = $this->aco->listPrivileges();
    //       $users = $this->aco->listUsersPrivileges();
    //       $groups = $this->aco->listGroupsPrivileges();
    $users = $privs['users'];
    $world = $privs['world'];
    $groups = $_SESSION[environment]->getGroupsParents($privs['groups']);

    $select_groups = array("users", "projects", "communities");

    parent::add("<P>");

    $box = new AMColorBox($_CMAPP['imlang_url']."/aco_tool.gif",AMColorBox::COLOR_BOX_PURPLE);
	
    //list the actual permissions of this aco
    //$box->add("<span id=topic>$_language[aco_title]</span>");

    

    $box->add("<div class='aco_privs_list' id='header'>$_language[aco_title]</div>");

    $icon = "<img src='$_CMAPP[images_url]/aco_world.gif'>";
    $box->add("<div class='aco_privs_list' id='item'>$icon $_language[aco_privs_world]</div>");

    foreach($groups as $group) {
      $temp = $group->project;
      if(empty($temp)) continue;
      $p = $temp->items[0];

      $thumb = new AMACOThumb;
      $thumb->codeArquivo = $p->image;
      try {
	$thumb->load();
      }
      catch(CMDBException $e) {
	echo $e; die();
      }

      $box->add("<div class='aco_privs_list' id='item'>");
      $box->add($thumb->getView());
      $box->add("$p->title</div>");
    }


    $box->add("<br><BUTTON TYPE=button class='image-button'><img src='$_CMAPP[imlang_url]/aco_bt_add_cell.gif' onClick='AM_showDiv(\"aco_listrender\")'></BUTTON>");

    $box->add("<br><br><div id=aco_listrender>");
    $box->add(new AMACOListRender);
    $box->add("</div>");
    parent::add($box);

    
    

    return parent::__toString();
  }

}


?>