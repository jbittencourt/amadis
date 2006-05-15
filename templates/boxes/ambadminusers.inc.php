<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminUsers extends AMSimpleBox { 

  public function __construct() {
    global $_language;    
    parent::__construct($_language[edit_people]);
  }

  public function __toString() {
    global $_language, $_CMAPP;

    $conteudo .= $_language[edit_people_n_groups]."<br>";
    $conteudo .= "<a href='$_CMAPP[services_url]/admin/editar_usuario.php'>".$_language[edit_people]."</a><br>";
    //    $conteudo .= "<a href='$_CMAPP[services_url]/admin/editar_grupo.php'>$_language[edit_group]</a>";
    $conteudo .= $_language[edit_group];
    parent::add($conteudo);    
    return parent::__toString();
  }

}

?>