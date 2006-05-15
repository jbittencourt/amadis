<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminAreas extends AMSimpleBox { 

  public function __construct() {
    global $_language;    
    parent::__construct($_language[edit_tables]);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    
    $area = new AMArea;

    switch($_REQUEST[acao]){
    case "deleteArea":   
      $area->codArea = $_REQUEST[idArea];
      try{
	$area->load();
	$area->delete();
      }catch(AMException $e){
      }  
      break;

    case "editArea":
      $area->codArea = $_REQUEST[idArea];
      $area->load();
      $area->nomArea = $_REQUEST[nomArea1];
      try{
	$area->save();        
      }catch(AMException $e){     
      }    
      break;

    case "addArea":
      $area->nomArea = $_REQUEST[nomArea];
      $area->codPai = 0 ;
      $area->intGeracao = 0;
      try{
	$area->save();   	
      }catch(AMException $e){     
      } 
      break;

    default:
      break;
    }

    parent::add("<table><tr><td><h3>".$_language['edit_area']."</h3></td></tr>");


    $res = $area->listaAreas();
    $i=0;
    foreach($res as $item){
      $conteudo .= "<tr><td>";
      $conteudo .= "$item->nomArea";
      $conteudo .= " &nbsp;&nbsp;( <a href='#' onClick='AM_togleDivDisplay(\"hideShowState".$i."\")'>".$_language['edit']."</a> | <a href='?acao=deleteArea&idArea=$item->codArea'>".$_language['delete']."</a> )</td></tr>";  

      $conteudo .= "<tr><td><span id='hideShowState".$i++."' style='display:none'><form action = $_SERVER[PHP_SELF] method=post>";
      $conteudo .= "<input type='text' value='$item->nomArea' name='nomArea1'> &nbsp;&nbsp;&nbsp;&nbsp;";
      $conteudo .= "<input type='hidden' name='acao' value='editArea'>";
      $conteudo .= "<input type='submit' value='".$_language['update']."'"; //trocar por uma imagem e fazer em ajax
      $conteudo .= "<input type='hidden' name='acao' value='editArea'>";
      $conteudo .= "<input type='hidden' name='idArea' value='$item->codArea'>";
      $conteudo .= "</form></span></td></tr>";

    }
    $conteudo .= "<tr><td><a href='#' onClick='AM_togleDivDisplay(\"hideShoww\")'>".$_language['add_new_area']."</a></td></tr>";
    $conteudo .= "<tr><td><span id='hideShoww' style='display:none'><form action = $_SERVER[PHP_SELF] method=post>";
    $conteudo .= "<input type='text' name='nomArea' value= '".$_language['area_name']."'> &nbsp;&nbsp;&nbsp;&nbsp;";
    $conteudo .= "<input type='hidden' name='acao' value='addArea'>";
    $conteudo .= "<input type='submit' value='".$_language['add']."'"; //trocar por uma imagem e fazer em ajax
    $conteudo .= "</form></span></td></tr>";
    $conteudo .= "</table>";

    parent::add($conteudo);  

    return parent::__toString();
  }

}

?>