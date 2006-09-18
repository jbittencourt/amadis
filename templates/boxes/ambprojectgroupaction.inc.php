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

    public function listGroup($codeGroup) {
        global $_language, $_CMAPP;

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

            $proj = new AMProjeto;
            $proj->codeGroup = $codeGroup;
            try {
                $proj->load();
            }catch(CMDBNoRecord $e){
                return "Cannot load project by the group code";
            }


            $adopt = new AMBProjectOrfan($proj);
            $adopt->setWidth($box->getWidth());
            $pag->add($adopt);
            $box->add($_language['no_members']."<br>");
        }

        $box->add("<br>");

        $ret['requires'] = $box->getRequires();
        $ret['list'] = utf8_encode($box->__toString());
        return $ret;
    }
    
    public function xoadGetMeta()
    {
        $methods = array('listGroup');
        XOAD_Client::mapMethods($this, $methods);

        XOAD_Client::publicMethods($this, $methods);
    }

}
