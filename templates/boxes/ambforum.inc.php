<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */


class AMBForum extends CMHTMLObj {

  protected $forums;
  protected $forumName;

  /**
   * Constructor of AMBForum.
   *
   * @param CMConstainer $forums Receive a list of forums that should be exibed.
   **/
  public function __construct($name, CMContainer $forums) {
    parent::__construct();
    $this->forums = $forums;
    $this->forumName = $name;
    $this->requires("forum.css",CMHTMLObj::MEDIA_CSS);

  }


  public function __toString() {  
    global $_CMAPP, $proj, $_language;


    parent::add("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
    parent::add("<tbody><tr>");
    parent::add("<td align=\"left\" height=\"25\" valign=\"top\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"5\">");
    parent::add("<img src=\"$_CMAPP[imlang_url]/img_foruns_topicos.gif\">");
    parent::add("</td>");
    parent::add("<td align=\"center\" valign=\"top\" width=\"70\">");
    parent::add("<img src=\"$_CMAPP[imlang_url]/img_forum_mensagens.gif\" align=\"bottom\" border=\"0\" height=\"11\" width=\"51\"></td>");
    parent::add("<td align=\"center\" valign=\"top\" width=\"100\">");
    parent::add("<img src=\"$_CMAPP[imlang_url]/img_forum_ultmpost.gif\" align=\"bottom\" border=\"0\" height=\"10\" width=\"76\">");
    parent::add("</td>");
    parent::add("</tr>");
    
    //link of the forum
    $link = "$_CMAPP[services_url]/forum/forum.php?frm_codeForum=";
    //initial color of the line
    $line_color_name = "01";
    //iterate throuth the forums and print them in the html
    foreach($this->forums as $item) {

      parent::add("<tr>");
      parent::add("<td class=\"forum_project_col\" id=\"a1\"><a href=\"$link$item->code\">$item->name</a>");

//       //temporary edit forum privileges (REMOVE)
//       parent::add("<A href='$_SERVER[PHP_SELF]?frm_action=edit_privs'>(privil&eacute;gios)</a>");
//       //END


      parent::add("<td class=\"forum_project_col\" id=\"a2\" align=\"center\">$item->numMessages</td>");
      parent::add("<td class=\"forum_project_col\" id=\"a1\" align=\"center\">");
      if($item->lastMessageTime>0) {
	 parent::add(date($_language['date_format'],$item->lastMessageTime));
      }
      parent::add("</td>");
      parent::add("</tr>");

      if($line_color_name == "01") {
	$line_color_name = "02";
      }
      else {
	$line_color_name = "01";
      }
    }

    //blank space line
    parent::add("<tr><td colspan=\"3\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"30\" width=\"10\"></td></tr>");

    parent::add("</tbody></table>");

    
    return parent::__toString();
  }


}


