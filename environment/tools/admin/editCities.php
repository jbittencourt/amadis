<?php

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

//soh imprime se o usuario for super
//if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {

$city = new AMCidade;

switch($_REQUEST[action]){
 case "delete":   
   $city->codCidade = $_REQUEST[id];
   try{
     $city->load();
     $city->delete();
     $pag->addMessage($_language['city_successful_del']);
   }catch(AMException $e){
     $pag->addError($_language["city_error_del"]);
   }  
   break;

 case "edit":
   $city->codCidade = $_REQUEST[id];
   $city->load();
   $city->nomCidade = $_REQUEST[nomCidade1];
   $city->codEstado =  $_REQUEST[codEstado1];
   try{
     $city->save();        
     $pag->addMessage($_language["city_successful_edit"]);
   }catch(AMException $e){     
     $pag->addError($_language["city_error_edit"]);
   }    
   break;

 case "add":
   $city->nomCidade = $_REQUEST[nomcidade];
   $city->codEstado =  $_REQUEST[desSigla];
   $city->tempo = time();
   try{
     $city->save();   
     $pag->addMessage($_language["city_successful_add"]);
   }catch(AMException $e){     
     $pag->addError($_language["city_error_add"]);
   } 
   break;

 default:
   break;
}

unset($_REQUEST);
$pag->add("<table><tr><td><h3>Editar Cidades:</h3></td></tr>");

$est = new AMEstado;
//------ Conta qntos estados existem cadastrados
$q = new CMQuery(AMEstado);
$q->setCount();
$numEstados = $q->execute();
//---------------------------------------------

$i=0;
for($j=0; $j< $numEstados;$j++){
  $est->codEstado = $j+1;
  /*try{
    $est->load();
  }catch(AMException $e){}
  $conteudo .= "$est->nomEstado :";*/
  $res = $est->listaCidades();
  foreach($res as $item){
    $conteudo .= "<tr><td>";
    $conteudo .= "$item->nomCidade - ";
    $conteudo .= " &nbsp;&nbsp;( <a href='#' onClick='AM_togleDivDisplay(\"hideShow".$i."\")'> Editar</a> | <a href='?action=delete&id=$item->codCidade'>Deletar</a> )</td></tr>";  
    /**
  $conteudo .= "<tr><td><span id='hideShow".$i++."' style='display:none'><form action = $_SERVER[PHP_SELF] method=post>";
  $conteudo .= "<input type='text' value='$item->nomEstado' name='nomEstado1'> &nbsp;&nbsp;&nbsp;&nbsp;";
  $conteudo .= "<input type='text' value='$item->desSigla' name='desSigla1' size='2'><br>";
  $conteudo .= "<input type='text' value='$item->desPais' name='desPais1'> &nbsp;&nbsp;&nbsp;&nbsp;";
  $conteudo .= "<input type='submit' value='Atualizar'"; //trocar por uma imagem e fazer em ajax
  $conteudo .= "<input type='hidden' name='action' value='edit'>";
  $conteudo .= "<input type='hidden' name='id' value='$item->codEstado'>";
  $conteudo .= "</form></span></td></tr>";**/

  }
}
$conteudo .= "<tr><td><a href='#' onClick='AM_togleDivDisplay(\"hideShoww\")'>Adicionar nova cidade</a></td></tr>";
/**  $conteudo .= "<tr><td><span id='hideShoww' style='display:none'><form action = $_SERVER[PHP_SELF] method=post>";
  $conteudo .= "<input type='text' name='nomEstado' value= 'Nome do estado'> &nbsp;&nbsp;&nbsp;&nbsp;";
  $conteudo .= "<input type='text' name='desSigla'  value= 'Sigla' size='2'><br>";
  $conteudo .= "<input type='text' name='desPais' value = 'Pais'> &nbsp;&nbsp;&nbsp;&nbsp;";
  $conteudo .= "<input type='hidden' name='action' value='add'>";
  $conteudo .= "<input type='submit' value='Adicionar'"; //trocar por uma imagem e fazer em ajax
  $conteudo .= "</form></span></td></tr>";**/
$conteudo .= "</table>";

$pag->add($conteudo);  
//}

//else die($lang[acesso_nao_permitido]);

echo $pag; 

?>
