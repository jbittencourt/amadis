<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMTLibraryFoto extends AMImageTemplate {

  public function __toString() {
    global $_CMAPP;

    $imagem_foto = $this->getImageURL(); 
    parent::add("<img src=\"$imagem_foto\" border=\"0\" class=\"boxdiario\">");
   
    return parent::__toString();
  } 
}

?>