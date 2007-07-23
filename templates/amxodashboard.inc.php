<?php
/**
 * DashBoard Menu that point to the user personal tools and links.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @category AMTemplate
 * @version 1.0
 * @author Juliano Bittencout <juliano@lec.ufrgs.br>
 * @see AMXOTheme, AMXOMenu
 */

class  AMXODashBoard extends CMHTMLObj
{
    public $locked;
    private $lines;



    function __construct()
    {
        global $_CMAPP,$_language;
        parent::__construct();

    	//$this->requires("menu.js",self::MEDIA_JS);

    	//adds the default items(lines)
        if(!isset($_SESSION['amadis']['menus'])) {
            $_SESSION['amadis']['menus'] = array("projects"=>0,
				     "friends"=>0,
				     "communities"=>0
            );
        }


            
        // STATUS MENU
        $str = "";
        $str .= "<div id=\"frame\">
			<div id=\"loginTopBorder\"></div>
					<div id=\"loginContent\">
						<ul>
							<li id=\"loginContentImage\">".$_SESSION['user']->username." $_language[on_AMADIS]:</li>
							<li id=\"loginStatus\">";
        $str.="					<form class=\"logon\" name=\"form5\" method=\"post\" action=\"\">";
        $str.="					<select id=\"loginStatusSelect\" name=\"select\" onChange=\"AMEnvSession.changeMode(this.value);\">";
        $modes = AMEnvSession::getModes();
        foreach($modes as $k=>$item) {
            if($_SESSION['session']->visibility == $k) $str.="<option value=\"$k\" SELECTED>$item</option>";
            else $str.="<option value=\"$k\">$item</option>";
        }
        $str.="					</select>";
        $str.= "			</li>
							<li id=\"loginExit\">
								<button id=\"logout_button\" type=\"button\" onclick=\"window.location='$_CMAPP[url]/index.php?login_action=A_logout'\"><div id=\"loginOutLeftImage\">&nbsp;&nbsp;</div><div id=\"loginOut\">$_language[exit]</div><div id=\"loginOutRightImage\">&nbsp;&nbsp;</div></button>	
							</li>
					</ul>	
				</div>
				<div id=\"loginBottomBorder\"></div>
			</div><br />";
      
		$this->add($str);
            
           
            
        //meu MENU
        
   		// MY PROJECTS
        $projects  = $_SESSION['user']->listMyProjects();
    	
        if($_SESSION['amadis']['menus']['projects']==1) $status = 'display:block;'; 
        else $status = 'display:none;';
        
        $click = "$('db-project-box').toggle(); changeMenuStatus('projects','db-project-box');";

        $temp  = '<div id="project_box">';
		$temp .= '  <div class="top"></div>';
		$temp .= '  <div class="db-title">';
		$temp .= '  <img onclick="'.$click.'"src="'.$_CMAPP['images_url'].'/xo/dashboard/icon_myprojects.png">';
		$temp .= '  <div class="title" onclick="'.$click.'">'.$_language['my_projects'].'</div>';
		$temp .= '  <br clear="all" />';
		$temp .= '  <div class="db-content" id="db-project-box" style="'.$status.'">';
		$temp .= '    <ul>';
			
        $temp .= "<li><a class='green' href='$_CMAPP[services_url]/projects/create.php'>&raquo; $_language[create_project]</a></li>";
        if($projects->__hasItems()) {
            foreach($projects as $proj) {
                $temp .= "<li><a href=\"".$_CMAPP['services_url']."/projects/project.php?frm_codProjeto=$proj->codeProject\" class=\"mnlateral\">&raquo; $proj->title</a><li>";
            }
        } else {
            $temp .= '<span class="texto">'.$_language['not_member_any_project'].'</span>';
		}
			
        $temp .= '    </ul>';
		$temp .= '  </div>';
		$temp .= '</div>';
		$temp .= '<div class="bottom"></div>';
		$temp .= '</div>';
        
		$this->add($temp);
            
		// MY FRIENDS
        $friends = $_SESSION['user']->listFriends();
        
        if($_SESSION['amadis']['menus']['friends']==1) $status = 'display:block;';
        else $status = 'display:none;';
        
       	$click = "$('db-friends-box').toggle(); changeMenuStatus('friends','db-friend-box')";
		$temp = '<div id="friends_box">';
		$temp .= '  <div class="top"></div>';
		$temp .= '  <div class="db-title">';
		$temp .= '    <img onclick="'.$click.'" src="'.$_CMAPP['images_url'].'/xo/dashboard/icon_myfriends.png">';
		$temp .= '    <div class="title" onclick="'.$click.'">'.$_language['my_friends'].'</div>';
		$temp .= '    <br clear="all" />';
		$temp .= '    <div class="db-content" id="db-friends-box" style="display:none;"><ul>';

        
        
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
                $temp .= "<li>$icoOnline<a class='mnlateral' href='".$_CMAPP['services_url']."/webfolio/userinfo_details.php?frm_codeUser=$friend->codeUser'> $friend->name</a></li>";
            }
        } else {
            $temp .= "<li class=\"texto\">$_language[no_friends]</li>\n";
        }

       	$temp .= '    </ul></div>';
		$temp .= '  </div>';
		$temp .= '  <div class="bottom"></div>';
		$temp .= '</div>';
          	
        $this->add($temp);
            
        // COMMUNITIES
        $communities = $_SESSION['user']->listMyCommunities();

        if($_SESSION['amadis']['menus']['communities']==1) $status = 'display:block;';
        $status = 'display:none;';
        
		$click = "$('db-community-box').toggle(); changeMenuStatus('communities','db-community-box')";
        $temp = '<div id="community_box">';
		$temp .= '  <div class="top"></div>';
		$temp .= '  <div class="db-title">';
		$temp .= '    <img onclick="'.$click.'" src="'.$_CMAPP['images_url'].'/xo/dashboard/icon_mycommunitys.png">';
		$temp .= '    <div class="title" onclick="'.$click.'">'.$_language['my_communities'].'</div>';
		$temp .= '    <br clear="all" />';
		$temp .= '    <div class="db-content" id="db-community-box" style="display:none;"><ul>';

        $temp .= "<li><a class='green' href='$_CMAPP[services_url]/communities/create.php'>&raquo; $_language[create_community]</a></li>";
        if($communities->__hasItems()) {
            foreach($communities as $communitie) {
               $str="";

               $temp .= "<li><a class=\"mnlateral\" href=\"".$_CMAPP['services_url']."/communities/community.php?frm_codeCommunity=$communitie->code\" class=\"mnlateral\">&raquo; $communitie->name $str</a></li>";
            }
        } else {
            $temp .= "<li>$_language[no_communities]</li>";
        }
        
        $temp .= '    </ul></div>';
		$temp .= '  </div>';
		$temp .= '  <div class="bottom"></div>';
		$temp .= '</div>';
        
		$this->add($temp);
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

		if(!empty($this->lines)) {
            foreach($this->lines as $line) {
                $img = "dot.gif";
                $bg="";
                parent::add($line['line']);
                if($line['line_after']) {
                    parent::add("<br />");
                }
            }
        }
		
		parent::add('<div id="db-menu">');
		parent::add('<div class="top"></div>');
		parent::add('<div class="db-content">');
		parent::add('<a href="'.$_CMAPP['services_url'].'/blog/blog.php"><img border="0" src="'.$_CMAPP['images_url'].'/xo/dashboard/icon_myblog.png"></a>');
		parent::add('<h3 class="db-blog-title"><a href="'.$_CMAPP['services_url'].'/blog/blog.php">'.$_language['blog'].'</a></h3>');

		parent::add('<a href="'.$_CMAPP['services_url'].'/library/biblioteca.php"><img src="'.$_CMAPP['images_url'].'/xo/dashboard/icon_mylibrary.png" border="0"></a>');
		parent::add('<h3 class="db-library-title"><a href="'.$_CMAPP['services_url'].'/library/biblioteca.php">'.$_language['library'].'</a></h3>');

		parent::add('<a href="'.$_CMAPP['services_url'].'/album/album.php"><img border="0" src="'.$_CMAPP['images_url'].'/xo/dashboard/icon_myalbum.png"></a>');
		parent::add('<h3 class="db-album-title"><a href="'.$_CMAPP['services_url'].'/album/album.php">'.$_language['album'].'</a></h3>');
		parent::add('</div>');
		parent::add('<div class="bottom"></div>');
		parent::add('</div>');
        
        return parent::__toString();

    }

}