<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */


class AMBProjectRequestAction {

  public function accept($project,$text) {
    global $_CMAPP,$_language;

    $ret = array ('success'=>false,
		  'request'=>$project,
		  'message'=>'',
		  'blockMmessage'=>'');

    $proj->codeProject = $project;
    try {
      $proj->load();
      $group = $proj->getGroup();
    } catch (CMObjException $e) {
      return "This project does not exists";
    }

    try {
      $group->acceptRequest($project,$text);
      $msg = new AMAlertBox(AMAlertBox::MESSAGE,$user->name." ".$_language['msg_user_added']);
      $ret['success'] = true;
    }
    catch(CMDBException $e) {
      $msg = new AMAlertBox(AMAlertBox::ERROR,$_language['error_joining_user']);
    }

    $ret['requires'] = $box->getRequires();
    $ret['message'] = $msg->__toString();
    return $ret;
  }


  public function reject() {
    global $_CMAPP,$_language;

    $ret = array ('success'=>false,
		  'message'=>'',
		  'blockMmessage'=>'',
		  'request'=>$project);

    $proj->codeProject = $project;
    try {
      $proj->load();
      $group = $proj->getGroup();
    } catch (CMObjException $e) {
      return "This project does not exists";
    }

    try {
      $group->rejectRequest($project,$text);
      $msg = new AMAlertBox(AMAlertBox::MESSAGE,$user->name." ".$_language['msg_rejected_user']);
      $ret['success'] = true;
    }
    catch(CMDBException $e) {
      $msg = new AMAlertBox(AMAlertBox::ERROR,$_language['error_joining_user']);
    }

    $ret['requires'] = $box->getRequires();
    $ret['message'] = $msg->__toString();
    return $ret;
  }


}

?>