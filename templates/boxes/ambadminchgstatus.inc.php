<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminChgStatus extends AMColorBox {
  
  public function __construct() {
    global $_CMAPP, $_language;
    parent::__construct($_language['chgstatus'],self::COLOR_BOX_BLUA);
  }
  
  public function __toString() {
    global $_language, $_CMAPP, $pag;
    
    
    $us = new AMUser;
    $us->codeUser = $_REQUEST['frm_codUser'];
    $us->load();
    
    if( $_REQUEST['action'] == "y"){
      if($us->active == "1"){
	$us->active = "0";
      }
      else{
	$us->active = "1";
      }
      try{
	$us->save();
      }catch(AMException $e) {
	//$pag->addError($_language['errorstatuschanged']);
	header("Location:$_SERVER[PHP_SELF]?frm_amerror=errorstatuschanged");
      }
      // $pag->addMessage($_language['statuschangedOk']);
      header("Location:$_SERVER[PHP_SELF]?frm_ammsg=statuschangedOk");
    }
    
    if($us->active == "1"){
      $message = $_language['desactive'];
    }
    else{
      $message = $_language['active'];
    }
    $image1 = new AMChgStatusThumb;
    $image1->codeArquivo = $us->foto;
    $image1->load();	  	

    $conteudo = "<table><tr><td>";
    parent::add($conteudo);
    parent::add($image1->getView());
    $conteudo = "</td><td valign='top'>".$message." ".$_language['user']." ".$us->username."?";
    $conteudo .= "<form action='?frm_codUser=$us->codeUser' method='post'><input type='radio' value='y' name='action'>".$_language['yes_please']."!<br><input type='radio' value='n' name='action' CHECKED>".$_language['no_thanks']."</td></tr>";
    $conteudo .= "<tr><td></td><td align='right'><input type='submit' value='".$_language['confirm']."'>";
    $conteudo .= "</td></tr></table>";
    
    
    parent::add($conteudo);
    
    return parent::__toString();    
  }
}

?>