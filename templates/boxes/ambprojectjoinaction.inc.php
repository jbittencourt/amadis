<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectJoinAction implements AMAjax {

	public function join($project,$text) {
    	global $_CMAPP;
    
    	$_language = $_CMAPP['i18n']->getTranslationArray('projects');


    	$proj = new AMProject;
    	$proj->codeProject = $project;
    	try {
      		$proj->load();
      		$group = $proj->getGroup();
    	} catch (CMObjException $e) {
      		return "This project does not exists";
    	}

    	$ret = array ('success'=>false,
			'message'=>'',
		  	'blockMmessage'=>'');

    	if(!empty($text)) {
      		try {
				$group->userRequestJoin($_SESSION['user']->codeUser,$text);
				$box = new AMAlertBox(AMAlertBox::MESSAGE,$_language['msg_join_request_send']);
				$ret['success'] = true;
				$ret['blockMessage'] = $_language['request_join_waiting'];
      		} catch(CMDBException $e) {
				$box = new AMAlertBox(AMAlertBox::ERROR,$_language['error_join_request']);
      		}
    	} else {
      		$box = new AMAlertBox(AMAlertBox::ERROR,$_language['error_no_explain_join_project']);
    	}

    	$ret['message'] = $box->__toString(); 
    	$ret['requires'] = $box->getRequires();


    	return $ret;
  	}
  
    public function xoadGetMeta() {
        $methods = array('join');
        XOAD_Client::mapMethods($this, $methods);
        XOAD_Client::publicMethods($this, $methods);
    }
}
?>