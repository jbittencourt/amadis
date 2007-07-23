<?php

/**
 * A list of projects inside an AMTCadBox.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMProject
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMListBox, AMTCadBox
 */
class AMProjectList extends AMListBox {


  /**
   *
   * @param CMContainter $item A container of projects to be listed.
   * @param string $title The title of the box.
   * @param integer $type The type the box. See AMTCabox for possible values.
   *
   * @see AMTCadbox
   **/
  public function __construct(CMContainer $items,$title,$type=AMTCadBox::CADBOX_SEARCH) {
    parent::__construct($items,$title, self::PROJECT, $type);
  }
  

  public function __toString() {
    global $_language,$_CMAPP;

    parent::add("<br /><br />");
    //listagem
    parent::add("<table id=\"project_list\">");
      
    if($this->itens->__hasItems()) {
      $i = 0;
      foreach($this->itens as $item) {
	$id = "project_list_1";
	if(($i%2)==1) $id = "project_list_2";
	$i++;
	parent::add("<tr id=\"$id\" class=\"project_list_line\">");
	  
	parent::add("<td>");
	
	$thumb = AMProjectImage::getThumb($item);
	parent::add($thumb->getView());
	  

	parent::add("<td width=40%>");
	parent::add("<a class=\"blue\" href=\"$_CMAPP[services_url]/projects/project.php?frm_codProjeto=$item->codeProject\">$item->title</a>");
	parent::add("</td>");
	parent::add("<td><span class=\"texto\">$item->description</span></td>");
	parent::add("</tr>");
      }
    }
    else {
      parent::add("$_language[no_project_found]");
    }

    parent::add("</table>");

    return parent::__toString();
  }
}