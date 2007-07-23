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
      
        $login = new AMBLogin();
        $this->add('loginbox_content',$login);
        $this->add('login_status', $_language['login']);
        if($_SESSION['environment']->logged) {
			$this->add('login_status', $_SESSION['user']->username.' '.$_language['on_AMADIS'].':');
			
        	if(!isset($_SESSION['amadis']['menus'])) {
                $_SESSION['amadis']['menus'] = array(
                		"projects"=>0,
					    "friends"=>0,
					    "communities"=>0
                );
            }
			
         // STATUS MENU
			if($_CMAPP['amadis_where_am_i']=='webfolio'){
            	$this->add('webfolio_active','_active');
            }
       		if($_CMAPP['amadis_where_am_i']=='blog'){
            	$this->add('blog_active', '_active');
            }
        	if($_CMAPP['amadis_where_am_i']=='library'){
            	$this->add('library_active', '_active');
            }	
            if($_CMAPP['amadis_where_am_i']=='projects'){
            	$this->add('project_active', '_active');
            }          
            if($_CMAPP['amadis_where_am_i']=='friends'){
            	$this->add('friends_active', '_active');
            }            

            
         // MY PROJECTS
            $projects  = $_SESSION['user']->listMyProjects();
            $temp = '<li id="projects_menu" class="project_txt" onclick="toggleActive(this, \'project\', \'1\');" >'.$_language['projects'];

            $tree = new AMTree($temp);
            $tree->setJSCall("changeMenuStatus('projects','$tree->name')");
			
            if($_SESSION['amadis']['menus']['projects']==1) {
                $tree->open();
            }

            $tree->add('<a class="green" href="'.$_CMAPP['services_url'].'/projects/create.php">&raquo; '.$_language['create_project'].'</a><br />');
            if($projects->__hasItems()) {
                foreach($projects as $proj) {
                    $str="";
                    $tree->add('<a href="'.$_CMAPP['services_url'].'/projects/project.php?frm_codProjeto='.$proj->codeProject.'" class="mnlateral">&raquo; '.$proj->title.' '.$str.'</a><br />');
                }
            }
            else {
                $tree->add('<span class="texto">'.$_language['not_member_any_project'].'</span>');
            }
 
            $this->add('projects_list', $tree->__toString());

            
         // MY FRIENDS
			
            $friends = $_SESSION['user']->listFriends();
       	
			$temp = '<li id="friends_menu" class="friends_txt" onclick="toggleActive(this, \'friends\', \'2\');" >'.$_language['friends'];
			            
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
                                $onClick = 'onclick="Finder_openChatWindow(\''.$_SESSION['user']->codeUser.'_'.$friend->codeUser.'\');"';
                                break;
                            case  AMFinder::FINDER_BUSY_MODE :
                                $ico = $_CMAPP['images_url']."/ico_user_ocupado.png";
                                $onClick = 'onclick="Finder_openChatWindow(\''.$_CMAPP['services_url'].'/finder/finder_chat.php?frm_codeUser='.$friend->codeUser.'\', \''.$_SESSION['user']->codeUser.'_'.$friend->codeUser.'\');"';
                                break;
                            case  AMFinder::FINDER_HIDDEN_MODE :
                                $ico = $_CMAPP['images_url']."/ico_user_off_line.png";
                                $onClick = 'onclick="Finder_openChatWindow(\''.$_CMAPP['services_url'].'/finder/finder_chat.php?frm_codeUser='.$friend->codeUser.'\', \''.$_SESSION['user']->codeUser.'_'.$friend->codeUser.'\');"';
                                break;
                        }
                    } else {
                        $ico = $_CMAPP['images_url'].'/ico_user_off_line.png';
                        $onClick = "";
	 
                    }
                    $icoOnline = '<img id="UserIco_'.$friend->codeUser.'" align="middle" src="'.$ico.'" $onClick alt="" />';
                    $tree->add($icoOnline.'<a class="mnlateral" href="'.$_CMAPP['services_url'].'/webfolio/userinfo_details.php?frm_codeUser='.$friend->codeUser.'"> '.$friend->name.'</a><br />');
                }
            } else {
                $tree->add('<span class="texto">'.$_language['no_friends'].'</span>');
            }

            $this->add('friends_list', $tree->__toString());
            

         // COMMUNITIES
            
            $communities = $_SESSION['user']->listMyCommunities();
            $temp = '<li class="commnunities_txt" onclick="toggleActive(this, \'commnunities\', \'3\');">'.$_language['communities'];
            $tree = new AMTree($temp);

            $tree->setJSCall("changeMenuStatus('communities','$tree->name')");

            if($_SESSION['amadis']['menus']['communities']==1) {
                $tree->open();
            }

            $tree->add("<a class='green' href='$_CMAPP[services_url]/communities/create.php'>&raquo; $_language[create_community]</a><br />");
            if($communities->__hasItems()) {
                foreach($communities as $communitie) {
                    $str="";

                    $tree->add("<a class=\"mnlateral\" href=\"".$_CMAPP['services_url']."/communities/community.php?frm_codeCommunity=$communitie->code\" class=\"mnlateral\">&raquo; $communitie->name $str</a><br />");
                }
            }
            else {
                $tree->add('<span class="texto">'.$_language['no_communities'].'</span>');
            }

            $this->add('communities_list', $tree->__toString());

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
   * @return Void
   **/
    public function add($key, $value)
    {
        $this->lines[$key] = $value;
    }


    public function __toString()
    {
        global $_CMAPP,$_language;

        if(!empty($this->lines)) {
        	parent::add(AMHTMLPage::loadView($this->lines, 'menu_box'));	
        }
        
        return parent::__toString();
    }
}