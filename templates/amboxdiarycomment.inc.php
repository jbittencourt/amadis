<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBoxDiaryComment extends CMHTMLObj {

  private $contents;

  /**
   * Pode-se adicionar uma pilha de strings html em um array 
   * ou um unico string html para ser que irah para a tela
   **/
  public function add($item) {
    $this->contents[] = $item;
  }


  function __toString() {
    global $_CMAPP;
    
    parent::add("<div class=\"diary_comment\">");
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\">");
    parent::add("<tr> ");
    parent::add("<td align=\"right\" width=10><img src=\"$_CMAPP[images_url]/box_cmnt_es.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");

    parent::add("<td bgcolor=\"#B5C5FF\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("<td width=10><img src=\"$_CMAPP[images_url]/box_cmnt_ds.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<tr> ");
    parent::add("<td bgcolor=\"B5C5FF\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("<td valign=\"top\" bgcolor=\"#B5C5FF\"> ");

    if(!empty($this->contents)) {
      parent::add($this->contents);
    }

    parent::add("</td>");
    parent::add("<td bgcolor=\"#B5C5FF\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<tr> ");
    parent::add("<td align=\"right\"><img src=\"$_CMAPP[images_url]/box_cmnt_ei.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("<td bgcolor=\"B5C5FF\"></td>");
    parent::add("<td><img src=\"$_CMAPP[images_url]/box_cmnt_di.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("</table>");
    parent::add("</div>");

    return parent::__toString(); 

  }


}




?>