<?

class AMBAdminCidades extends AMSimpleBox { 

  public function __construct() {
    global $_language;
    
    parent::__construct($_language[edit_tables]);
  }

  public function __toString() {
    global $_language, $_CMAPP;

    $city = new AMCidade;

    switch($_REQUEST[action]){
    case "deleteCity":   
      $city->codCidade = $_REQUEST[idCidade];
      try{
	$city->load();
	$city->delete();
      }catch(AMException $e){
      }  
      break;
      
    case "editCity":
      $city->codCidade = $_REQUEST[idCidade];
      $city->load();
      $city->nomCidade = $_REQUEST[nomCidade1];
      $city->codEstado =  $_REQUEST[codEstado1];
      try{
	$city->save();        
      }catch(AMException $e){ 
      }    
      break;
      
    case "addCity":
      $city->nomCidade = $_REQUEST[nomCidade];
      $city->codEstado =  $_REQUEST[codEstado];
      $city->tempo = time();
      try{
	$city->save();   
      }catch(AMException $e){     
      } 
      break;
     
    }

    parent::add("<table><tr><td><h3>". $_language['edit_cities']. "</h3></td></tr>");

    $est = new AMEstado;
    //------ Conta qntos estados existem cadastrados
    $q = new CMQuery(AMEstado);
    $extados = $q->execute();
    //---------------------------------------------

    $i=0;
    foreach($extados as $it){
      $est->codEstado = $it->codEstado;
      $res = $est->listaCidades();

      foreach($res as $item){
	$conteudo .= "<tr><td>";
	$conteudo .= $item->nomCidade;
	$conteudo .= " &nbsp;&nbsp;( <a href='#' onClick='AM_togleDivDisplay(\"hideShow".$i."\")'>".$_language['edit']."</a> | <a href='?action=deleteCity&idCidade=$item->codCidade'>".$_language['delete']."</a> )</td></tr>";  
	//----------formulario
	$conteudo .= "<tr><td><span id='hideShow".$i++."' style='display:none'><form action = '?action=editCity' method=post>";
	$conteudo .= "<input type='text' value='$item->nomCidade' name='nomCidade1'><br>";
	$conteudo .= "<select name='codEstado1'>";
	//----
	$ee = new AMEstado; $he = $ee->listaEstados();
	foreach($he as $items){
	  if($items->codEstado == $item->codEstado)
	    $conteudo .= "<option value='$items->codEstado' selected>$items->nomEstado</option>";
	  else
	    $conteudo .= "<option value='$items->codEstado'>$items->nomEstado</option>";
	}
	//----  
	$conteudo .= "</select>";
	$conteudo .= "<input type='hidden' name='action' value='editCity'>";
	$conteudo .= "<input type='hidden' name='idCidade' value='$item->codCidade'>";
	$conteudo .= "<input type='submit' value='".$_language['update']."'"; //trocar por uma imagem e fazer em ajax
	$conteudo .= "</form></span></td></tr>";

      }
    }
    $conteudo .= "<tr><td><a href='#' onClick='AM_togleDivDisplay(\"hideShowAddCity\")'>".$_language['add_new_city']."</a></td></tr>";
    $conteudo .= "<tr><td><span id='hideShowAddCity' style='display:none'><form action = '?action=addCity' method=post>";
    $conteudo .= "<input type='text' value='".$_language['city_name']."' name='nomCidade'>";
    $conteudo .= "<select name='codEstado'>";
    //----
    $ee = new AMEstado; $he = $ee->listaEstados();
    $conteudo .= "<option value='' selected>".$_language['select_state']."</option>";
    foreach($he as $items){
      $conteudo .= "<option value='$items->codEstado'>$items->nomEstado</option>";
    }
    //----  
    $conteudo .= "</select>";
    $conteudo .= "<input type='submit' value='".$_language['add']."'"; //trocar por uma imagem e fazer em ajax
    $conteudo .= "<input type='hidden' name='action' value='addCity'>";
    $conteudo .= "<input type='hidden' name='idCidade' value='$item->codCidade'>";
    $conteudo .= "</form></span></td></tr>";
    $conteudo .= "</table>";

    parent::add($conteudo);  
    
    return parent::__toString();
  }

}

?>