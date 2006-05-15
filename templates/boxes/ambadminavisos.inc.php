<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminAvisos extends AMSimpleBox { 

  public function __construct() {
    global $_language;
    
    parent::__construct($_language[edit_tables]);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    $aviso = new AMAviso;
    $acao = $_REQUEST[acao];
    if($acao == "send"){

      $aviso->titulo = $_REQUEST[titulo];
      $aviso->descricao = $_REQUEST[text];
      $aviso->tempoInicio = time();
      
      if($_REQUEST[endTime] != 0)
	$tempoFinal = $aviso->tempoInicio + ($_REQUEST[endTime] * 24 * 60 * 60);
      else
	$tempoFinal = $aviso->tempoInicio;
      
      $aviso->tempoFim = $tempoFinal;
      try{
	$aviso->save();
      }catch(AMException $e){ $conteudo = "Problemas ao enviar"; }     
    }
    elseif($acao == "delete"){
      $aviso->codeAviso = $_REQUEST[id];
      $aviso->load();
      try{
	$aviso->delete();
      }catch(AMException $e){ $conteudo = "Problemas ao deletar"; }     
    }
    elseif($acao == "edit"){
      $aviso->codeAviso = $_REQUEST[id];
      $aviso->load();
      $aviso->titulo = $_REQUEST[titulo];
      $aviso->descricao = $_REQUEST[text];
      
      try{
	$aviso->save();
      }catch(AMException $e){  }     
    }


    $i=0;
    $res = $aviso->listaAvisos();
    foreach($res as $item){
      if($item->tempoFim > time() || ($item->tempoFim - $item->tempoInicio) == 0){ //caso o aviso seja corrente
	$conteudo .= "<tr><td>$item->titulo";
	$conteudo .= " &nbsp;&nbsp;( <a href='#' onClick='AM_togleDivDisplay(\"hideShow".$i."\")'>".$_language['edit']."</a> | <a href='?acao=delete&id=$item->codeAviso'>".$_language['delete']."</a> )</td></tr>";  
	
	$conteudo .= "<tr><td><span id='hideShow".$i++."' style='display:none'><form action = $_SERVER[PHP_SELF] method=post>";
	$conteudo .= "<input type='text' value='$item->titulo' name='".$_language['title']."'><br>";
	$conteudo .= "<textarea name ='text'>$item->descricao</textarea><br>";
	$conteudo .= "<input type='hidden' name='acao' value='edit'>";
	$conteudo .= "<input type='hidden' name='id' value='$item->codeAviso'>";
	$conteudo .= "<input type='submit' value='".$_language['update']."'"; //trocar por uma imagem e fazer em ajax
	$conteudo .= "</form></span></td></tr>";
      }
    }
	/////////////////////////
    $conteudo .= "<table><tr><td><h3>".$_language['send_warn']."</h3></td></tr>";
    $conteudo .= "<tr><td><form action = $_SERVER[PHP_SELF] method=post>";
    $conteudo .= "<input type='text' value='".$_language['warn_title']."' name='titulo' size=24><br>";
    $conteudo .= "<textarea name ='text'>".$_language['text']."</textarea><br>";
    $conteudo .= $_language['warn_time']."<br>";
    $conteudo .= "<input type='radio' name='endTime' value='1'>".$_language['one_day'];
    $conteudo .= "<input type='radio' name='endTime' value='7'>".$_language['one_week'];
    $conteudo .= "<input type='radio' name='endTime' value='14'>".$_language['two_weeks']."<br>";
    $conteudo .= "<input type='radio' name='endTime' value='30'>".$_language['one_mouth']."";
    $conteudo .= "<input type='radio' name='endTime' value='180'>".$_language['six_mouths']."";
    $conteudo .= "<input type='radio' name='endTime' value='365'>".$_language['one_year']."";
    $conteudo .= "<input type='radio' name='endTime' value='0' checked>".$_language['infinity']."<br>";
    $conteudo .= "<input type='hidden' name='acao' value='send'>";
    $conteudo .= "<input type='submit' value='".$_language['send_warn']."'"; //trocar por uma imagem e fazer em ajax
    $conteudo .= "</form></td></tr></table>";
    parent::add($conteudo);  
    
    return parent::__toString();
  }
  
}
?>