<?

class AMBAdminEstados extends AMSimpleBox { 

  public function __construct() {
    global $_language;
    
    parent::__construct($_language[edit_tables]);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    
    $est = new AMEstado;
    switch($_REQUEST[action1]){
    case "deleteState":   
      $est->codEstado = $_REQUEST[idState];
      try{
	$est->load();
	$est->delete();	
      }catch(AMException $e){
      }  
      break;

    case "editState":
      $est->codEstado = $_REQUEST[idState];
      $est->load();
      $est->nomEstado = $_REQUEST[nomEstado1];
      $est->desSigla =  $_REQUEST[desSigla1];
      $est->desPais = $_REQUEST[desPais1];
      try{
	$est->save();        
      }catch(AMException $e){     
      }    
      break;

    case "addState":
      $est->nomEstado = $_REQUEST[nomEstado];
      $est->desSigla =  $_REQUEST[desSigla];
      $est->desPais = $_REQUEST[desPais];
      try{
	$est->save();   	
      }catch(AMException $e){     
      } 
      break;

    default:
      break;
    }

    parent::add("<table><tr><td><h3>".$_language['edit_state']."</h3></td></tr>");


    $res = $est->listaEstados();
    $i=0;
    foreach($res as $item){
      $conteudo .= "<tr><td>";
      $conteudo .= "$item->nomEstado - $item->desSigla - $item->desPais";
      $conteudo .= " &nbsp;&nbsp;( <a href='#' onClick='AM_togleDivDisplay(\"hideShowState".$i."\")'>".$_language['edit']."</a> | <a href='?action1=deleteState&idState=$item->codEstado'>".$_language['delete']."</a> )</td></tr>";  

      $conteudo .= "<tr><td><span id='hideShowState".$i++."' style='display:none'><form action = $_SERVER[PHP_SELF] method=post>";
      $conteudo .= "<input type='text' value='$item->nomEstado' name='nomEstado1'> &nbsp;&nbsp;&nbsp;&nbsp;";
      $conteudo .= "<input type='text' value='$item->desSigla' name='desSigla1' size='2'><br>";
      $conteudo .= "<input type='text' value='$item->desPais' name='desPais1'> &nbsp;&nbsp;&nbsp;&nbsp;";
      $conteudo .= "<input type='hidden' name='action1' value='editState'>";
      $conteudo .= "<input type='hidden' name='idState' value='$item->codEstado'>";
      $conteudo .= "<input type='submit' value='".$_language['update']."'"; //trocar por uma imagem e fazer em ajax

      $conteudo .= "</form></span></td></tr>";

    }
    $conteudo .= "<tr><td><a href='#' onClick='AM_togleDivDisplay(\"hideShoww\")'>".$_language['add_new_state']."</a></td></tr>";
    $conteudo .= "<tr><td><span id='hideShoww' style='display:none'><form action = $_SERVER[PHP_SELF] method=post>";
    $conteudo .= "<input type='text' name='nomEstado' value= '".$_language['state_name']."'> &nbsp;&nbsp;&nbsp;&nbsp;";
    $conteudo .= "<input type='text' name='desSigla'  value= '".$_language['acronym']."' size='2'><br>";
    $conteudo .= "<input type='text' name='desPais' value = '".$_language['country']."'> &nbsp;&nbsp;&nbsp;&nbsp;";
    $conteudo .= "<input type='hidden' name='action1' value='addState'>";
    $conteudo .= "<input type='submit' value='".$_language['add']."'>"; //trocar por uma imagem e fazer em ajax
    $conteudo .= "</form></span></td></tr>";
    $conteudo .= "</table>";

    parent::add($conteudo);  

    return parent::__toString();
  }

}

?>