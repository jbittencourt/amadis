<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMFinder extends CMHTMLPage {

  function __construct() {
    parent::__construct();
    //precisa pq usa o wtreenode
    $this->width=281;
    $this->requires("divs.js");
    $this->requires("finder.js");
    $this->requires("amadis.css.php?theme=green","CSS");
    $this->setMargin(0,0,0,0);
  }


  function add($line) {
    $this->contents[] = $line;
  }  


  function __toString() {
    
    global $_language, $_CMAPP;


    
    $this->setTitle($_language['finder']);
    $this->setBgImage("$urlimagens/quebra_cabeca_03.gif");

    parent::add("<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" height=\"100%\" width=\"100%\" style=\"table-layout: fixed\" background=\"$urlimagens/quebra_cabeca_02.gif\">");

    parent::add("<tbody>");
    parent::add("<tr>");
    parent::add("<td valign=\"top\" style=\"background-repeat: no-repeat\" background=\"$urlimagens/quebra_cabeca_01.gif\">");


    /********************
     * Dados que sao colocados dentro do menu
     */

    if(!empty($this->contents)) {
      foreach($this->contents as $line)
	parent::add($line);
    }    
    

    /********************
     * FIM dos dados
     */

    parent::add("</table>");

    return parent::__toString();

  }

}



?>
