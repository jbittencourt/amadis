<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminSendNotif extends AMColorBox {

  public function __construct() {
    global $_CMAPP, $_language;
    parent::__construct($_language['sendn_button'],self::COLOR_BOX_BLUA);
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
      
      if($_REQUEST['acao'] == "send"){
	
	$id = $_REQUEST['frm_codeUser'];
	$message  = $_REQUEST['notif'];
	unset($_REQUEST);
	
	//lendo o campo <adminemail> la do config.xml
	$_conf = $_CMAPP[config]->getObj();
	$email_admin = (string) $_conf->app->general->admin_email;
	
	$email = new CMWSimpleMail($email_admin,$_language['adminsector']);
	
	$email->setSubject($_language['subjectNotify']);
	$email->setMessage($message);
	$email->addTo($user->email, $user->name);
	$email->setReplyTo($email_admin);
	
	$stat = $email->send();
	
	if($stat == ""){
	  //  header("Location:]?frm_ammsg=notify_successful_sent");
	}else{
	  //header("Location:$_SERVER[PHP_SELF]?frm_amerror=notify_error_sent");
	}
      }
      
      $conteudo = "<table><tr><td>".$user->name."(".$user->email.")</td></tr>";
      $conteudo .= "<tr><td><form method='post' action='$_SERVER[PHP_SELF]?frm_codUser=$user->codeUser&acao=send'>";
      $conteudo .= "<br><br>";
      $conteudo .= "<textarea name='notif' ROWS='6' COLS='35'></textarea></td></tr>";
      $conteudo .= "<tr><td align='right'><input type='submit' value='".$_language['send']."'>";
      $conteudo .= "</form></td></tr></table>";
      
      parent::add($conteudo);
    }else{
      parent::add($_language['user_not_found']);
    }
    
    
    return parent::__toString();    
  }
    
}

?>