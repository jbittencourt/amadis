<?


class AMBGroupRequestAction {

  static public $_lang;

  public function __construct() {
    global $_CMAPP;

    self::$_lang =  $_CMAPP['i18n']->getTranslationArray('projects');
  }


  public function accept($codeRequest,$codeGroup,$codeUser,$text) {
    global $_CMAPP;



    $ret = array ('success'=>false,
		  'request'=>$codeRequest,
		  'message'=>'',
		  'group'=>$codeGroup,
		  'blockMessage'=>'');

    try {
      $group = new CMGroup;
      $group->codeGroup = $codeGroup;
      $group->load();
    } catch (CMObjException $e) {
      return "This Group does not exists";
    }

    try {
      $user = new CMUser;
      $user->codeUser = $codeUser;
      $user->load();
    } catch (CMObjException $e) {
      return "This User does not exists";
    }


    try {
      $group->acceptRequest($codeRequest,$text);
      $msg = new AMAlertBox(AMAlertBox::MESSAGE,$user->name." ".self::$_lang['msg_user_added']);
      $ret['success'] = true;
    }
    catch(CMDBException $e) {
      $msg = new AMAlertBox(AMAlertBox::ERROR,$_language['error_joining_user']);
    }

    $ret['requires'] = $msg->getRequires();
    $ret['message'] = $msg->__toString();
    return $ret;
  }


  public function reject($codeRequest,$codeGroup,$codeUser,$text) {
    global $_CMAPP,$_language;

    $ret = array ('success'=>false,
		  'request'=>$codeRequest,
		  'message'=>'',
		  'group'=>$codeGroup,
		  'blockMessage'=>'');
    try {
      $group = new CMGroup;
      $group->codeGroup = $codeGroup;
      $group->load();
    } catch (CMObjException $e) {
      return "This project does not exists";
    }

    try {
      $user = new CMUser;
      $user->codeUser = $codeUser;
      $user->load();
    } catch (CMObjException $e) {
      return "This User does not exists";
    }


    try {
      $group->rejectRequest($codeRequest,$text);
      $msg = new AMAlertBox(AMAlertBox::ERROR,$user->name." ".self::$_lang['msg_user_rejected']);
      $ret['success'] = true;
    }
    catch(CMDBException $e) {
      $msg = new AMAlertBox(AMAlertBox::ERROR,$_language['error_joining_user']);
    }

    $ret['requires'] = $msg->getRequires();
    $ret['message'] = $msg->__toString();
    return $ret;
  }


}

?>