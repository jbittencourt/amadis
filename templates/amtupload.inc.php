<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

include($_CMAPP['path']."/templates/ammain.inc.php");


class AMTUpload extends AMMain {

  private $itens = array();
  private $subTitle, $thumb;
  
  public function __construct($img_top) {
    global $_CMAPP;
    parent::__construct();

    $this->setImgId($_CMAPP['imlang_url']."/$img_top");

    $this->openNavMenu();
  }
  
  public function setTitle($title) {
    $this->subTitle = $title;
  }

  public function setThumb($thumb) {
    $this->thumb = $thumb;
  }
  
  public function add($item) {
    $this->itens[] = $item;
  }

  public function __toString() {
    
    global $_CMAPP, $_language;
    
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"20\" height=\"20\">");
    parent::add("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"\">");
    parent::add("  <tr>");
    parent::add("    <td width=\"10\">");
    parent::add("      <img src=\"$_CMAPP[images_url]/box_upload_azul_01.gif\" border=\"0\" height=\"10\" width=\"10\">");
    parent::add("    </td>");
    parent::add("    <td bgcolor=\"#8ad2f9\">");
    parent::add("      <img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\">");
    parent::add("    </td>");
    parent::add("    <td width=\"10\">");
    parent::add("      <img src=\"$_CMAPP[images_url]/box_upload_azul_02.gif\" border=\"0\" height=\"10\" width=\"10\">");
    parent::add("    </td>");
    parent::add("  </tr>");
    parent::add("  <tr>");
    parent::add("    <td bgcolor=\"#8ad2f9\">");
    parent::add("       <img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\">");
    parent::add("    </td>");
    parent::add("    <td bgcolor=\"#8ad2f9\" valign=\"top\">");
    parent::add("<!-- cabelho do upload -->");

    //header
    parent::add("<table cellpadding='0' cellspacing='0' border='0'>");
    parent::add(" <tr>");
    parent::add("   <td><img class='imgtitupload' src='$this->thumb'></td>");
    parent::add("   <td width='20'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
    parent::add("   <td valign='top'><font class='titupload'>$_language[edit_page]</font><br>");
    parent::add("   <font class='subtitupload'>".$this->subTitle."</font>");
    parent::add("   </td>");

    parent::add("   <td width='20'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
    parent::add(" </tr>");
    parent::add("</table>");
    //fim do header

    parent::add("    </td>");
    parent::add("    <td bgcolor=\"#8ad2f9\">");
    parent::add("      <img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\">");
    parent::add("    </td>");
    parent::add("  </tr>");
    parent::add("<!-- fim cabelho do diario -->");
    parent::add(" <tr bgcolor=\"#8ad2f9\">");
    parent::add("<td colspan=\"3\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\">");
    parent::add("</td>");
    parent::add("    </tr>");
    parent::add("    <tr bgcolor=\"#8ad2f9\">");
    parent::add("      <td bgcolor=\"#8ad2f9\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\">");
    parent::add("</td>");
    parent::add("      <td bgcolor=\"#fafbfb\" valign=\"top\">");

    /*
     *itens da pagina
     */
    if(!empty($this->itens)) {
      foreach($this->itens as $item) {
	parent::add($item);
      }
    }

    parent::add("  </td>");
    parent::add("  <td bgcolor=\"#8ad2f9\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("    </tr>");
    parent::add("    <tr bgcolor=\"#8ad2f9\">");
    parent::add("  <td colspan=\"3\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("  <td>");
    parent::add("<img src=\"$_CMAPP[images_url]/box_upload_azul_03.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("<td bgcolor=\"#8ad2f9\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
    parent::add("  <td><img src=\"$_CMAPP[images_url]/box_upload_azul_04.gif\" border=\"0\" height=\"10\" width=\"10\">");
    parent::add("</td>");
    parent::add("</tr>");
    parent::add("</table>");
    
    return parent::__toString();
  }
}



?>