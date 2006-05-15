<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMMenuBox extends CMHTMLObj {


  /*
   * Pode-se adicionar uma pilha de strings html em um array 
   * ou um unico string html para ser que irah para a tela
   */
  public function add($item) {
    $this->contents[] = $item;
  }



  public function __toString() {
    global $_CMAPP;
    
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"174\">");
    parent::add("<tr>");
    parent::add("<td colspan=\"3\"><img src=\"$_CMAPP[images_url]/mn_box_01.gif\" width=\"174\" height=\"9\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("<td valign=\"top\" background=\"$_CMAPP[images_url]/mn_box_bg.gif\">");
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">");
    parent::add("  <tr>");
    parent::add("  <td><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"7\" height=\"10\"></td>");

    parent::add("  <td>");

    if(!empty($this->contents)) {
      foreach($this->contents as $item) {
	parent::add($item);
      }
    }

    parent::add("  </tr>");
    parent::add("</table>");
    parent::add("</td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("<td colspan=\"3\"><img src=\"$_CMAPP[images_url]/mn_box_02.gif\" width=\"174\" height=\"9\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<!-- fim login -->");
    parent::add("</td>");
    parent::add("</tr>");
    parent::add("</table>");

    return parent::__toString();
  }



}