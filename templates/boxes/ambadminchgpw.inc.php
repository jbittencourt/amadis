<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminChgPw extends AMColorBox {

  public function __construct() {
    global $_CMAPP, $_language;
    parent::__construct($_language['chgpw'],self::COLOR_BOX_BLUA);
  }

  public function __toString() {
    global $_language, $_CMAPP, $pag;
    
    $user = new AMUser;

    if(isset($_REQUEST['frm_codUser'])){
      $user->codeUser = $_REQUEST['frm_codUser'];
      try{
	$user->load();
      }catch(AMException $e){
	$e->getMessage();
      }

      if($_REQUEST['acao'] == "insert"){
	if($_REQUEST['newpw'] == $_REQUEST['rnewpw']){
	  $user->password  = $_REQUEST['newpw'];
	  try{
	    $user->save();
	    //$pag->addMessage($_language["pw_successful_change"]);
	    header("Location:$_SERVER[PHP_SELF]?frm_ammsg=pw_successful_change");
	  }catch(AMException $e){  }
	}
	else { 
	  header("Location:$_SERVER[PHP_SELF]?frm_amerror=pw_notmatch");
	  // $pag->addError($_language['pw_notmatch']); 
	}
      }
      $conteudo .= "<table><tr><td>";
      $conteudo .= "<form method='post' action='$_SERVER[PHP_SELF]?frm_codUser=$user->codeUser&acao=insert'></td></tr>";
      $conteudo .= "<tr><td>".$_language['set_new_pw']." ". $user->name."</td></tr><tr><td><input type='password' name='newpw'></td></tr><tr><td>".$_language['retype_new_pw']."<br><input type='password' name='rnewpw'></td></tr><tr><td align='right'><input type='submit' value='".$_language['change']."'>";
      $conteudo .= "</form>";
      $conteudo .= "</td></tr></table>";
   
      }else{
	$conteudo = $_language['user_not_found'];
      }
      
      parent::add($conteudo);
      
      return parent::__toString();    
    }
}

?>