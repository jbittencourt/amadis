<?php
/**
 * The default vizualization of the user picture.
 *
 * This class render a user picture inside a gigsaw.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto, AMFile, AMUserFoto
 */
class AMTUserImage extends AMImageTemplate {


  public function __toString() {
    global $_CMAPP;

    parent::add('<div class="user-image">');
    parent::add("<table border=0 cellpadding='0' cellspacing='0'>");
    parent::add("<tr><td colspan='2' align='left'><img src='$_CMAPP[images_url]/perfil_01.gif'></td>");
    parent::add("<tr><td valign='top'><img src='$_CMAPP[images_url]/perfil_02.gif'></td>");
    parent::add("<td valign='top'  background='$_CMAPP[images_url]/perfil_bg.gif'>");
    $url = $this->getImageURL();
    parent::add("<img src='$url'  class='box'>");
    parent::add("</table>");
    parent::add('</div>');
    
    return parent::__toString();
  } 
}