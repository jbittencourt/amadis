<?
class AMBAdminAlfabeto extends AMColorBox {

  public function __construct() {
    global $_CMAPP, $_language;
    parent::__construct($_language['select_initial'],self::COLOR_BOX_BLUA);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    $alfabeto = array();
    for($i = 97; $i < 122; $i++)
      $alfabeto[chr($i)] = chr($i);

    foreach($alfabeto as $item )
      $conteudo .= "<a href='?initial=$item'>".strtoupper($item)."</a> - ";
    
    $conteudo .= "<a href='?initial=all'>".$_language['all']."</a>";

    parent::add($conteudo);
    
    return parent::__toString();    
  }
}

?>