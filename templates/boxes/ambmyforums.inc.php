<?


class AMBMyForums extends CMHtmlObj {

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

    //head of the table
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"500\">");

    parent::add("<tr>");
    parent::add("<td bgcolor=\"#f7f9f9\" width=\"10\"><img src=\"$_CMAPP[images_url]/box_forum_01.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("<td bgcolor=\"#f7f9f9\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("<td bgcolor=\"#f7f9f9\" width=\"10\"><img src=\"$_CMAPP[images_url]/box_forum_02.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("</tr>");

    //title of the table
    parent::add("<tr>");
    parent::add("<td bgcolor=\"#f7f9f9\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("<td class=\"forum_project_title\"  bgcolor=\"#f7f9f9\"><img src=\"$_CMAPP[imlang_url]/img_forum_participante.gif\"></td>");
    parent::add("<td bgcolor=\"#f7f9f9\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("</tr>");

    //blank line
    parent::add("<tr bgcolor=\"#f7f9f9\"><td colspan=\"3\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"30\" width=\"20\"></td></tr>");

    //head of the internal table
    parent::add("<tr bgcolor=\"#fafbfb\">");
    parent::add("<td><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("<td valign=\"top\">");

    parent::add("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
    parent::add("<tbody><tr>");
    parent::add("<td align=\"left\" height=\"25\" valign=\"top\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"5\">");
    parent::add("<img src=\"$_CMAPP[imlang_url]/img_foruns_topicos.gif\">");
    parent::add("</td>");
    parent::add("<td align=\"center\" valign=\"top\" width=\"70\">");
    parent::add("<img src=\"$_CMAPP[imlang_url]/img_forum_mensagens.gif\" align=\"bottom\" border=\"0\" height=\"11\" width=\"51\"></td>");
    parent::add("</tr>");
    
    //link of the forum
    $link = "$_CMAPP[services_url]/forum/forum.php?frm_codeForum=";
    //initial color of the line
    $line_color_name = "01";
    //iterate throuth the forums and print them in the html
    foreach($this->forums as $item) {

      parent::add("<tr>");
      parent::add("<td class=\"forum_project_col\" id=\"a1\"><a href=\"$link$item->code\">$item->name</a></td>");
      parent::add("<td class=\"forum_project_col\" id=\"a2\" align=\"center\">$item->newMessages</td>");
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
    //line with create forum image

    parent::add("</tbody></table>");

    //line in the bottom of the second table
    parent::add("<td><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("<td><img src=\"$_CMAPP[images_url]/box_forum_03.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("<td bgcolor=\"#f7f9f9\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("<td><img src=\"$_CMAPP[images_url]/box_forum_04.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("</tr>");


    parent::add("</tbody></table>");
    
    return parent::__toString();
  }


}


