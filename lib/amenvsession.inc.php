<?

/** Esta classe serve somente para gerenciar eventos do cliente,
 *  ligados a manipulacao da interface.
 */
class AMEnvSession {
  
  public function changeMenuStatus($name, $status) {
    $_SESSION['amadis']['menus'][$name] = $status;
  }

  /**
   * Muda o modo do usuario.
   *
   * O modo do usuario deve estar dentro do array $this->modos. Ele faz a alteracao necesseria
   * no campo visibilidade da tabela sessao_ambiente(RDSessaoAMbiente). A secao do usuario esta 
   * registrada em $_SESSION[ambiente].
   *
   * @Param string $mensagem Mensagem a ser enviada
   * @param integer $para Codigo do usuario para quem se deseja enviar um mensagem
   */
  public function changeMode($mode) {
    
    if(!empty($_SESSION['session'])) {
     
      $_SESSION['session']->visibility = $mode;
      $_SESSION['session']->save();
    
    } else {
      throw new AMEFinderEmptyEnvironment;
    };

  }

  public function getNewRequests() {
    
    $q = new CMQuery('AMFinderMessages');
    
    $filter = "codeRecipient = ".$_SESSION['user']->codeUser." AND time >".(time()-6);
    
    $q->setFilter($filter);
    
    $result $q->execute();
    if($result->__hasItems()) {
      foreach($result as $item) {
	$id = $_SESSION['user']->codeUser."_$item->codeSender";

	if(isset($_SESSION['amadis']['FINDER_ROOM'][$id]) || empty($_SESSION['amadis']['FINDER_ROOM'][$id])) {
	  $_SESSION['amadis']['FINDER_ROOM'][$id] = array("sender"=>$_SESSION['user']->codeUser,
							  "recipient"=>$item->codeSender,
							  "time"=>time(),
							  "wait"=>array(),
							  "open"=>false;
							  );
	} else {
	  $_SESSION['amadis']['FINDER_ROOM'][$id]['wait'][] = $item;
	  $_SESSION['amadis']['FINDER_ROOM'][$id]['time'] = time();
	}
      }
    } else return 0;
    
  }

  /**Retorna os modos de visualizacao do usuario
   *
   */
  static public function getModes() {
    global $_language;
    
    $modes[AMFinder::FINDER_NORMAL_MODE] = $_language["finder_mode_".AMFinder::FINDER_NORMAL_MODE];
    $modes[AMFinder::FINDER_BUSY_MODE] = $_language["finder_mode_".AMFinder::FINDER_BUSY_MODE];
    $modes[AMFinder::FINDER_HIDDEN_MODE] = $_language["finder_mode_".AMFinder::FINDER_HIDDEN_MODE];
    
    return $modes;
    
  }
  

  public function makeFriend($codeUser, $time, $comentay, $msg, $msg_err) {

    $ret = array("divId"=>$codeUser);

    try {
      $friend = new AMFriend;
      $friend->codeFriend = $codeUser;
      $friend->codeUser = $_SESSION['user']->codeUser;
      if(!empty($comentary)) {
	$friend->comentary = $_REQUEST['frm_comentary'];
      }
      $friend->status = AMFriend::ENUM_STATUS_ACCEPTED;
      $friend->time = time();
      $friend->save();
      unset($_SESSION['amadis']['friends']);
      $box = new AMAlertBox(AMAlertBox::MESSAGE, $msg);
    }catch(CMException $e) {
      $box = new AMAlertBox(AMAlertBox::ERROR, $msg_err);
    }
    
    $ret['msg'] = $box->__toString();

    return $ret;

  }

  public function rejectFriend($codeUser, $time, $msg, $msg_err) {

    $ret = array("divId"=>$codeUser);
    
    try {
      $friend = new AMFriend;
      $friend->codeFriend = $codeUser;
      $friend->codeUser = $_SESSION['user']->codeUser;
      $friend->status = AMFriend::ENUM_STATUS_REJECTED;
      $friend->time = time();
      $friend->save();
      $box = new AMAlertBox(AMAlertBox::MESSAGE, $msg);      
    }catch(CMException $e) {
      $box = new AMAlertBox(AMAlertBox::ERROR, $msg_err);
    }
    
    $ret['msg'] = $box->__toString();

    return $ret;

  }

}

?>