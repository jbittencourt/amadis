<?php
/**
 * This box lists the active requests from users to join a project
 *
 * @package AMADIS
 * @subpackage AMBoxes
 * @autho Juliano Bittencourt <juliano@lec.ufrgs.br>
 **/
class AMBProjectRequest extends AMColorBox
{
    
    protected $requests;
    protected $proj;

    public function __construct(AMProject $proj)
    {
        global $_CMAPP;

        $this->requires("group.css",CMHTMLObj::MEDIA_CSS);
        $this->requires("group.js",CMHTMLObj::MEDIA_JS);
        $this->requires("projectjoin.js",CMHTMLObj::MEDIA_JS);
        $this->proj = $proj;
        $group = $proj->getGroup();

        parent::__construct("",self::COLOR_BOX_BLUE);

        $this->requests = $group->listGroupJoinRequests();

        $this->name = "projectRequestBox";

        AMMain::addXOADHandler('AMBGroupRequestAction', 'AMBGroupRequest');

    }

    public function hasRequests()
    {
        return $this->requests->__hasItems();
    }

    public function __toString()
    {
        global $_language,$_CMAPP;

        $_first = true;
        $proj = $this->proj;
        foreach($this->requests as $user) {

            $code = $user->request[0]->codeGroupMemberJoin;
            $codeGroup =  $user->request[0]->codeGroup;
            $codeUser = $user->codeUser;


            if(!$_first) {
                parent::add("<div>");
                parent::add(new AMDotline("100%"));
                parent::add('</div>');
                parent::add("</div>");
            }
            
      //user foto thumbnail

            parent::add("<div id='request-$code'>");
            parent::add("<table border=0 cellspacing=1 cellpadding=2 width=\"100%\">");
            parent::add("<tr><td>");
            $thumb = new AMUserThumb;
            try {
            	$thumb->codeFile = $user->picture;
            	try {
            		$thumb->load();
            		parent::add($thumb->getView());
            	}catch(CMException $e) {
            		new AMLog('AMBProjectRequest load thumbnail', $e, AMLog::LOG_PROJECTS);
            	}
            }catch(CMException $e) {
            	new AMLog('AMBProjectRequest setting codeFile', $e, AMLog::LOG_PROJECTS);
            }
			
      //an empty column
            parent::add("</td><td><img src=\"$_CMAPP[images_url]/dot.gif\" width=10>");

      //invitation text
            parent::add("</td><td class=\"texto\">");
            parent::add(new AMTUserInfo($user));
            parent::add("$_language[user_join_request] ");

            $reason = $user->request[0]->textRequest;
            parent::add('<br><br><span class="texto">'.$reason.'</span>');
            parent::add("</td>");

            parent::add("</tr><tr><td colspan=3 class='response-button'>");



            $input.= "<input type=hidden name=frm_codeGroupJoin value='$code'>";
            $input.= "<input type=hidden name=frm_codProjeto value='$proj->codeProject'>";

            $img_accept = "<img id='accept-button' onclick=\"doRequest('accept-box-',$code)\" class='response-button' src='$_CMAPP[imlang_url]/ico_aceitar.gif'>";
            $img_reject = "<img id='reject-button' onclick=\"doRequest('reject-box-',$code)\" class='response-button' src='$_CMAPP[imlang_url]/ico_rejeitar.gif'>";
            $img_cancel = "<img id='cancel-button'class='response-button' src='$_CMAPP[imlang_url]/ico_ignorar.gif'>";

            parent::add('<span id="group-buttons-'.$code.'" class="group-buttons">'.$img_accept.' '.$img_reject.'</span>');

            parent::add("<div id='accept-box-$code' class='response-box'>");
            parent::add("<form id='join-request-accept-$code'  method=\"post\" action=\"$_SERVER[PHP_SELF]\">");
            parent::add("<font class=texto>$_language[message]</font><br>");
            parent::add("<textarea cols=27 rows=5 name=\"frm_text\"></textarea><br>");
            parent::add($input);
            parent::add("<button onClick=\"acceptUserJoin($code,$codeGroup,$codeUser)\" type=button>$img_accept</button>");
            parent::add("<button  onClick=\"doRequestCancel($code)\" type=button>$img_cancel</button>");
            parent::add("</form>");
            parent::add("</div>");


            parent::add("<div id='reject-box-$code' class='response-box'>");
            parent::add("<form id='join-request-reject-$code' method=\"post\" action=\"$_SERVER[PHP_SELF]\">");
            parent::add("<font class=texto>$_language[message]</font><br>");
            parent::add("<textarea cols=27 rows=5 name=\"frm_text\"></textarea><br>");
            parent::add("<input type=hidden name=req_action value='A_reject'>");
            parent::add($input);
            parent::add("<button onClick=\"rejectUserJoin($code,$codeGroup,$codeUser)\" type=button>$img_reject</button>");
            parent::add("<button  onClick=\"doRequestCancel($code)\" type=button>$img_cancel</button>");

            parent::add("</form>");
            parent::add("</div>");

            parent::add("</tr>");
            parent::add("</table>");

            $_first = false;
        }
        parent::add("</div>");

        $count = $this->requests->count();
        parent::add(CMHTMLObj::addScript("GroupMembersRequestCount = $count;"));

        return parent::__toString();
    }

}