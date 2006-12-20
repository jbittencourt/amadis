<?php
/**
 * Vertical Menu that point to the user personal tools and links.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @category AMTemplate
 * @version 1.0
 * @author Juliano Bittencout <juliano@lec.ufrgs.br>
 * @see AMMain, AMMainMenu
 */

class  AMNavMenu extends CMHTMLObj
{
    public $locked;
    private $lines;



    function __construct()
    {
        global $_CMAPP,$_language;
        parent::__construct();
    //precisa pq usa o wtreenode
        $this->width=281;
    //$this->requires("menu.js",self::MEDIA_JS);

    //adds the default items(lines)
        if(!$_SESSION['environment']->logged) {
            $login = new AMBLogin();
            $this->add($login,true,true);

            $box = new AMMenuBox;
            $box->add("<a href=\"$_CMAPP[services_url]/webfolio/recoverpassword.php\" ><img src=\"$_CMAPP[imlang_url]/img_esqueci.gif\" border=0></a>");
            $this->add($box,true,false);
  
        }
        else {

            if(!isset($_SESSION['amadis']['menus'])) {
                $_SESSION['amadis']['menus'] = array("projects"=>0,
					     "friends"=>0,
					     "communities"=>0
                );
            }

            $str ="	 <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">";
            $str.="	 <tr>";
            $str.="	 <td valign=\"top\" width=\"25\"><img src=\"$_CMAPP[images_url]/img_mn_login_on.gif\" width=\"25\" height=\"19\" border=\"0\"></td>";
            $str.="<td class=\"mnlateral\">".$_SESSION['user']->username." $_language[on_AMADIS]:<br>";
            $str.="<form class=\"logon\" name=\"form5\" method=\"post\" action=\"\">";
      //select com os modos 
            
            $str.="<select name=\"select\" onChange=\"AMEnvSession.changeMode(this.value);\">";

            $modes = AMEnvSession::getModes();
            foreach($modes as $k=>$item) {
                if($_SESSION['session']->visibility == $k) $str.="<option value=\"$k\" SELECTED>$item</option>";
                else $str.="<option value=\"$k\">$item</option>";
            }
            $str.="</select>";
            $str.="</form>";
            $str.="<button id=\"logout_button\" type=\"button\" onclick=\"window.location='$_CMAPP[url]/index.php?login_action=A_logout'\">";
            $str.="<img class=\"btsair\" src=\"$_CMAPP[imlang_url]/bt_sair.gif\" align=\"right\"></td>";
            $str.="</button>";
            $str.="	 </tr>";
            $str.="	 </table>";
            $box = new AMMenuBox;
            $box->add($str);
            $this->add($box,true,true);



      // WEBFOLIO
            $str = "<a href=\"$_CMAPP[services_url]/webfolio/webfolio.php\">";
            $str.= "<img border=\"0\" src=\"".$_CMAPP['imlang_url']."/mn_meu_webfolio.gif\"></a>";
            $this->add($str,true);

      // MY DIARY
            $img = "<img src=\"$_CMAPP[imlang_url]/mn_meu_meudiario.gif\" border=0>";
            $this->add("<a href=\"$_CMAPP[services_url]/blog/blog.php\">$img</a>");

      // MY LIBRARY
            $img = "<img src=\"$_CMAPP[imlang_url]/mn_meus_arquivos.gif\" border=0>";
            $this->add("<a href=\"$_CMAPP[services_url]/library/biblioteca.php\">$img</a>");

      // MY PROJECTS
            $projects  = $_SESSION['user']->listMyProjects();
            $temp = "<img border=\"0\" src=\"".$_CMAPP['imlang_url']."/mn_meu_meusprojetos.gif\">";

            $tree = new AMTree($temp);
            $tree->setJSCall("changeMenuStatus('projects','$tree->name')");

            if($_SESSION['amadis']['menus']['projects']==1) {
                $tree->open();
            }

            $tree->add("<a class='green' href='$_CMAPP[services_url]/projects/create.php'>&raquo; $_language[create_project]</a><br>");
            if($projects->__hasItems()) {
                foreach($projects as $proj) {
                    $str="";
                    $tree->add("<a href=\"".$_CMAPP['services_url']."/projects/project.php?frm_codProjeto=$proj->codeProject\" class=\"mnlateral\">&raquo; $proj->title $str</a><br>");
                }
            }
            else {
                $tree->add('<span class="texto">'.$_language['not_member_any_project'].'</span>');
            }
            $this->add($tree,false,false,$_CMAPP['images_url']."/mn_bgmeus.gif");

      // MY FRIENDS
            $friends = $_SESSION['user']->listFriends();
            $temp = "<img src=\"$_CMAPP[imlang_url]/mn_meu_meusamigos.gif\" border=0>";
            $tree = new AMTree($temp);

            $tree->setJSCall("changeMenuStatus('friends','$tree->name')");
            if($_SESSION['amadis']['menus']['friends']==1) {
                $tree->open();
            }

            if($friends->__hasItems()) {

                foreach($friends as $friend) {
                    if(!isset($_SESSION['amadis']['onlineusers'][$friend->codeUser])) {
                        $_SESSION['amadis']['onlineusers'][$friend->codeUser] = array();
                        $_SESSION['amadis']['onlineusers'][$friend->codeUser]['flagEnded'] = CMEnvSession::ENUM_FLAGENDED_ENDED;
                        $_SESSION['amadis']['onlineusers'][$friend->codeUser]['visibility'] = AMFinder::FINDER_NORMAL_MODE;
                    }
                     
                    if($_SESSION['amadis']['onlineusers'][$friend->codeUser]['flagEnded'] == CMEnvSession::ENUM_FLAGENDED_NOT_ENDED) {

                        switch($_SESSION['amadis']['onlineusers'][$friend->codeUser]['visibility']) {
                            case AMFinder::FINDER_NORMAL_MODE:
                                $ico = "$_CMAPP[images_url]/ico_user_on_line.png";
                                $onClick = "onclick=\"Finder_openChatWindow('".$_SESSION['user']->codeUser."_$friend->codeUser');\"";
                                break;
                            case  AMFinder::FINDER_BUSY_MODE :
                                $ico = $_CMAPP['images_url']."/ico_user_ocupado.png";
                                $onClick = "onclick=\"Finder_openChatWindow('$_CMAPP[services_url]/finder/finder_chat.php?frm_codeUser=$friend->codeUser', '".$_SESSION['user']->codeUser."_$friend->codeUser');\"";
                                break;
                            case  AMFinder::FINDER_HIDDEN_MODE :
                                $ico = $_CMAPP['images_url']."/ico_user_off_line.png";
                                $onClick = "onclick=\"Finder_openChatWindow('$_CMAPP[services_url]/finder/finder_chat.php?frm_codeUser=$friend->codeUser', '".$_SESSION['user']->codeUser."_$friend->codeUser');\"";
                                break;
                        }
                    } else {
                        $ico = $_CMAPP['images_url']."/ico_user_off_line.png";
                        $onClick = "";
	 
                    }
                    $icoOnline = "<img id=\"UserIco_$friend->codeUser\" align=\"middle\" src=\"$ico\" $onClick>";
                    $tree->add("$icoOnline<a class=\"mnlateral\" href=\"".$_CMAPP['services_url']."/webfolio/userinfo_details.php?frm_codeUser=$friend->codeUser\"> $friend->name $str</a><br>");
                }
            } else {
                $tree->add("<font class=\"texto\">$_language[no_friends]</font>");
            }
            
            $this->add($tree,false,false,$_CMAPP['images_url']."/mn_bgmeus.gif");
            $communities = $_SESSION['user']->listMyCommunities();
            $temp = "<img src=\"$_CMAPP[imlang_url]/mn_minhas_comunidades.gif\" border=0>";
            $tree = new AMTree($temp);

            $tree->setJSCall("changeMenuStatus('communities','$tree->name')");

            if($_SESSION['amadis']['menus']['communities']==1) {
                $tree->open();
            }

            $tree->add("<a class='green' href='$_CMAPP[services_url]/communities/create.php'>&raquo; $_language[create_community]</a><br>");
            if($communities->__hasItems()) {
                foreach($communities as $communitie) {
                    $str="";

                    $tree->add("<a class=\"mnlateral\" href=\"".$_CMAPP['services_url']."/communities/community.php?frm_codeCommunity=$communitie->code\" class=\"mnlateral\">&raquo; $communitie->name $str</a><br>");
                }
            }
            else {
                $tree->add("<font class=\"texto\">$_language[no_communities]</font>");
            }
            $this->add($tree,false,false,$_CMAPP['images_url']."/mn_bgmeus.gif");
            $this->add("<img src=\"$_CMAPP[imlang_url]/mn_box_footer.gif\" border=0>");

        }
    }


  /**
   * Adds a line to the menu.
   * 
   * The menu is formed by a table with many lines, each one containing an item
   * to the user. The first parameter is the line been added. The second force
   * the menu to put an image (orange trace) connecting the item to the blue line.
   * The third parameter force the menu to draw an empty line(height=20) after the
   * the item.
   *
   * @param String $line - Line been added
   * @param Boolean $connector - Force put an image in menu
   * @param Boolean $forceEmptyLineAfter - Force add an empty line after new item in menu
   * @param String $bg - Background color
   * @return Void
   **/
    public function add($line,$connector=false,$forceEmptyLineAfter=false,$bg="")
    {
        $this->lines[] = array("line"=>$line,
			   "connector"=>$connector,
			   "line_after"=>$forceEmptyLineAfter,
			   "bg"=>$bg
        );
    }


    public function __toString()
    {
        global $_CMAPP,$_language;

    //adds the items inside the menu
        parent::add('<div id="nav-menu">');
        parent::add('<div id="column-one">');

        if(!empty($this->lines)) {
            foreach($this->lines as $line) {
                parent::add("<tr>");
                if($line['connector']) {
                    $img = "mn_traco_laranja.gif";
                }
                else {
                    $img = "dot.gif";
                }
                $bg="";
                parent::add('<div class="portlet-line">');
                parent::add('<div id="orange-line"><img src="'.$_CMAPP['images_url'].'/'.$img.'"></div>');
                parent::add('<div class="portlet" style=\'background-image: url("'.$_CMAPP['images_url'].'/'.$line['bg'].'")\'>');
                parent::add($line['line']);
                parent::add('</div>');
                parent::add('</div>');

                if($line['line_after']) {
                    parent::add("<br>");
                }
            }
        }

        
    //end of items
        parent::add('<div id="footer-menu"><img src="'.$_CMAPP['images_url'].'/img_footer_menu.gif"></div>');
        parent::add('</div>');
        parent::add('</div>');


        return parent::__toString();

    }

}