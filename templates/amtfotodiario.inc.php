<?


class AMTFotoDiario extends AMImageTemplate {

  public function __toString() {
    global $_CMAPP;

    $imagem_foto = $this->getImageURL(); 
    parent::add("<img src=\"$imagem_foto\" border=\"0\" class=\"boxdiario\" alt\"$this->textoProfile\">");
   
    return parent::__toString();
  } 
}
?>