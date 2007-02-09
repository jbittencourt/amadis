<?php
 /**
 * @package AMADIS
 * @subpackage AMProject
 */

class AMBProjectGroupAction implements AMAjax
{
    static public $_lang;

    public function __construct() {
        global $_CMAPP;

        if(empty(self::$_lang)) {
            self::$_lang =  $_CMAPP['i18n']->getTranslationArray('projects');
        }
    }

    public function adoptProject($codeProject) 
    {

    	$proj = new AMProject;
    	$proj->codeProject = $codeProject;
    	try {
    		$proj->load();
    	}catch(CMDBNoRecord $e) {
    		new AMErrorReport($e, 'AMBProjectGroupAction', AMLog::LOG_PROJECTS);
    		return 'Cannot load project or group';
    	}
     	
    	$group = $proj->getGroup();
      	$group->force_add = true;
      	try {
			$group->addMember($_SESSION['user']->codeUser);
    	} catch(CMDBException $e) {
			$err = new AMError(self::$_lang['error_joining_user'],get_class($this));
			return false;
      	}

      	$msg = new AMMessage($user->name." ".$_language['msg_project_adopted'],get_class($this));
      	$this->adopted = true;

      	return $this->listGroup($group->codeGroup);
    }
    
    public function listGroup($codeGroup) 
    {
        global $_CMAPP;
		
        self::$_lang =  $_CMAPP['i18n']->getTranslationArray('projects');
        
        $ret = array ('success'=>true,
		  'list'=>'');

        $group = new CMGroup;
        $group->codeGroup = $codeGroup;
				
        try{
            $group->load();
            
        }catch(CMDBNoRecord $e){
            return "Cannot load project or group";
        }

		
        $projMembers = $group->listActiveMembers();

        $orfan = false;
        $box = new AMBox("","");

        $count = 0;
        if($projMembers->__hasItems()) {
            foreach($projMembers as $item) {
                $temp = new AMTUserInfo($item);
                $temp->setClass("text");
                $box->add($temp);
                $box->add("<br>");
                if($count>10) break;
                else $count++;
            }
        } else {
            $orfan = true;
			
            $proj = new AMProject;
            $proj->codeGroup = $codeGroup;
            try {
                $proj->load();
            }catch(CMDBNoRecord $e){
                return "Cannot load project by the group code";
            }

            $adopt = new AMBProjectOrfan($proj);
            $adopt->setWidth($box->getWidth());
            $box->add($adopt);
            $box->add(self::$_lang['no_members']."<br>");
        }
		
        $box->add("<br>");
        $ret['requires'] = $box->getRequires();
        $ret['list'] = utf8_encode($box->__toString());
        return $ret;
    }
    
    public function xoadGetMeta()
    {
        $methods = array('listGroup','adoptProject');
        XOAD_Client::mapMethods($this, $methods);

        XOAD_Client::publicMethods($this, $methods);
    }

}
