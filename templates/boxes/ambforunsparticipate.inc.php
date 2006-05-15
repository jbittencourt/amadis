<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBForunsParticipate extends CMHTMLObj {
  
  private $itens = array();
  protected $forums;
    
  public function __construct(CMContainer $forums) {
    global $_CMAPP;
    parent::__construct("");
       
    $this->forums = $forums;
  }

  public function __toString() {   
    global $_CMAPP, $_language;


    parent::add("<div id=\"forums_particitape\">");
    parent::add("<img src=\"$_CMAPP[imlang_url]/tit_foruns_participante.gif\">");

    if($this->forums->__hasItems()) {
      foreach($this->forums as $item) {
	$class = "webfolio_forum";
	$newMsgsStr = "";
	if($item->newMessages>0) {
	  $class = "webfolio_forum_new";
	  $newMsgsStr = "($item->newMessages)";
	}
	parent::add("<br><span class=\"$class\"><a href=\"$_CMAPP[services_url]/forum/forum.php?frm_codeForum=$item->code\">&raquo; $item->name $newMsgsStr</a></span>");
      }
    } else {
      parent::add("<br><span class=\"$class\">$_language[no_forums_events]</span>");
    }

    parent::add("<br><img src=\"$_CMAPP[images_url]/box_traco.gif\">");
    parent::add("<br><a href=\"$_CMAPP[services_url]/forum/userforums.php\" class=\"bluebox\">$_language[forum_list_more]</a>");
    parent::add("</div>");

    return parent::__toString();
  }

}

?>
