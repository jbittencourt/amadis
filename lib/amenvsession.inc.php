<?
/**
 * This class make management events of the client interface.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunication
 * @category AMAjax
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */

class AMEnvSession implements AMAjax {
  
  /**
   * Change menu status, to opened or closed.
   * This function affects only AMNavMenu
   *
   * @param string $name - menuItem name
   * @param string $status - menuItem status
   * @see AMNavMenu
   */
  public function changeMenuStatus($name, $status) {
    $_SESSION['amadis']['menus'][$name] = $status;
  }
  
  /**
   * Change the user mode visualization.
   *
   * The user mode must be in a array $this->modos. It make the necessary change
   * in the visibility field of the CMEnvSession class. The user session is registered in
   * $_SESSION[environment] variable.
   *
   * @param string $mode - Visualization mode
   */
  public function changeMode($mode) {
    
    if(!empty($_SESSION['session'])) {
     
      $_SESSION['session']->visibility = $mode;
      $_SESSION['session']->save();
    
    } else {
      throw new AMEFinderEmptyEnvironment;
    };

  }


  public function getFinderRequest() {

    //nao checar novas requisicoes para chats abertos
    $sql = array();
    foreach($_SESSION['amadis']['FINDER_ROOM'] as $item) {
      if($item['open'] == 1) {
	$sql[] = " codeSender != $item[recipient] ";
      }
    }

    $sql = implode(" AND ", $sql);
    
    
    $q = new CMQuery('AMFinderMessages');

    $projection  = "AMFinderMessages::code, AMFinderMessages::codeSender, AMFinderMessages::message, ";
    $projection .= "AMUser::codeUser, AMUser::foto, AMUser::username";

    $q->setProjection($projection);

    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMUser');
    $j->on("AMUser::codeUser = AMFinderMessages::codeSender");

    $q->addJoin($j, "user");
    
    if(!empty($sql)) {
      $filter = "codeRecipient = ".$_SESSION['user']->codeUser." AND $sql AND AMFinderMessages::time > ".(time()-60);
    }else $filter = "codeRecipient = ".$_SESSION['user']->codeUser." AND AMFinderMessages::time > ".(time()-60);


    $q->setFilter($filter);

    $result = $q->execute();
    notelastquery();
    $ret = array();
    
    if($result->__hasItems()) {
      foreach($result as $item) {

	$id = $_SESSION['user']->codeUser."_$item->codeSender";
	
	$tip = new AMBFinderTip($item->user[0], $item);
	
	$ret[$item->codeSender] = array();
	$ret[$item->codeSender]['tip'] = $tip->__toString();
	$ret[$item->codeSender]['id'] = $id;

	if(!isset($_SESSION['amadis']['FINDER_ROOM'][$id])) {
	  $_SESSION['amadis']['FINDER_ROOM'][$id] = array("sender"=>$_SESSION['user']->codeUser,
							  "recipient"=>$item->codeSender,
							  "time"=>time(),
							  "wait"=>array($item->code=>serialize($item)),
							  "open"=>0
							  );
	} else if($_SESSION['amadis']['FINDER_ROOM'][$id]['open'] == 0) {
	  $_SESSION['amadis']['FINDER_ROOM'][$id]['wait'][$item->code] = serialize($item);
	  $_SESSION['amadis']['FINDER_ROOM'][$id]['time'] = time();
	} 
      }
      return $ret;
      
    } else return 0;
    
  }

  /**
   * Get the visualization modes for a user
   *
   * @access static public
   * @param void
   * @return Array $modes - list of visualization modes
   */
  static public function getModes() {
    global $_language;
    
    $modes[AMFinder::FINDER_NORMAL_MODE] = $_language["finder_mode_".AMFinder::FINDER_NORMAL_MODE];
    $modes[AMFinder::FINDER_BUSY_MODE] = $_language["finder_mode_".AMFinder::FINDER_BUSY_MODE];
    $modes[AMFinder::FINDER_HIDDEN_MODE] = $_language["finder_mode_".AMFinder::FINDER_HIDDEN_MODE];
    
    return $modes;
    
  }

  /**
   * Add a user as a friend.
   *
   * @access public
   * @param $codeUser - AMADIS user_id
   * @param $time - Unixtime when a user was added
   * @param $comentary - A litle comment for a user
   * @param $msg - The programer success-message
   * @param $msg_err - The programer error_message
   * @return Array $ret - success/error message to user
   */
  public function makeFriend($codeUser, $time, $comentay, $msg, $msg_err) {

    $ret = array("divId"=>$codeUser);

    try {
      $friend = new AMFriend;
      $friend->codeFriend = $codeUser;
      $friend->codeUser = $_SESSION['user']->codeUser;
      $friend->load();
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

  /**
   * Reject a friend invitation.
   *
   * @access public
   * @param $codeUser - AMADIS user_id
   * @param $time - Unixtime when a user was added
   * @param $comentary - A litle comment for a user
   * @param $msg - The programer success-message
   * @param $msg_err - The programer error_message
   * @return Array $ret - Success/error message to user
   */
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

  public function xoadGetMeta() {
    $methods = array('changeMenuStatus', 'changeMode', 'getFinderRequest', 'getModes', 'makeFriend', 'rejectFriend');
    XOAD_Client::mapMethods($this, $methods);
    XOAD_Client::publicMethods($this, $methods);
  }

}

?>