<?php
/**
 * The AMMain is the main template in AMADIS.
 *
 * The AMMain class is the main template in AMADIS. It creates
 * the default visualization of the environment, adding the menu,
 * the navigation menu (navmenu), and loading the default css and
 * javascript files.
 *
 * @package AMADIS
 * @subpackage Core
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMNavMenu
 **/
class AMMain extends AMHTMLPage 
{
    protected $main_menu;
    protected $contents;
    protected $slidein;
    protected $navmenu;
    protected $auto_error_report = true;
    protected $pathindicator;
    protected static $XOADHandlers = array();
    protected $alerts, $notifications = array();
	protected $page;
	
    function __construct($theme="") 
    {
        global $_CMAPP, $_conf;
        parent::__construct();
        
        switch( (string) $_conf->app->interface->theme ) {
        	case 'xo':
        		$this->page = new AMXOTheme; break;
        	default:
        		$this->page = new AMDefaultTheme; break;
        }
		$this->page->section = $theme;
        $this->page->mainMenu = new AMMainMenu();

        $this->page->navMenu = new AMNavMenu();
		
        $this->requires('prototype.js', CMHTMLObj::MEDIA_JS );
        $this->requires('scriptaculous/scriptaculous.js?load=effects', CMHTMLObj::MEDIA_JS );        
        $this->requires("tooltip.css",self::MEDIA_CSS);
        $this->requires("lib.js",self::MEDIA_JS);
        $this->requires("finder.js", self::MEDIA_JS);
        $this->requires("finder.css", self::MEDIA_CSS);
        $this->requires("envsession.js", self::MEDIA_JS);
        $this->requires("tipms.css", self::MEDIA_CSS);
        

    /** Set Finder hash table
     *
     */
        if(!isset($_SESSION['amadis']['FINDER_ROOM']))
        $_SESSION['amadis']['FINDER_ROOM'] = array();

    /** Set onLineUsers hash table
     *
     */
        if(!isset($_SESSION['amadis']['onlineusers']))
        $_SESSION['amadis']['onlineusers'] = array();

    /** Set the communicator array and class requires
     *
     */
        $_SESSION['communicator'] = array();
        $_SESSION['XOADHandlers'] = array();
    }

    
    function add($line) 
    {
        $this->page->contents[] = $line;
    }

    function setMenuSuperior($img1,$img2,$img3) 
    {
        $this->tema[0]= $img1;
        $this->tema[1]= $img2;
        $this->tema[2]= $img3;
    }

    function setSectionTitle($title)
    {
    	$this->page->sectionTitle = $title;
    }
    
    function setImgId($img) 
    {
        $this->page->imgid = $img;
    }
    
    function setLeftMargin($w) 
    {
        $this->leftMargin = $w;
    }


  /**
   * This function disable the automatic error capture.
   *
   * The AMMain automaticaly do an error message capture,
   * that seeks for an frm_amerror parameter in the actual
   * request and transform it in an error. This function 
   * makes the actual page to ignore the frm_amerror.
   **/
    public function disableAutoError() 
    {
        $this->auto_error_report = 0;
    }

    
  /**
   * Adds an error message to this page.
   *
   * This function adds an standard error message in the actual page. AMMain also search in the
   * page request for an frm_amerror parameter.  In this case the parameter
   * message is an suffix to an entry in the language file, so the error messages can
   * be transalated. The key that will be searched in the language file is in the
   * "error_$message" format. The use of the entire errro message in the
   * url shold be very limited, so we adopted this strategy.
   *
   * <code>
   *   header($_CMAPP[services_url]."/projetos/create.php?frm_amerror=project_exists");
   * </code>
   * 
   * This function will search for an key in the language file named error_projet_exists. Note
   * that the error message should be in global or in the selected language section.
   *
   * If a debug option is enable, a new AMError is gerenated to show a exception messages.
   * This form is better to show messages errors to developers, without affect final users.
   *
   * @param String $message The message suffix 
   * @param object $e An associated exception
   * @see AMMain::addMessage(), AMMain::addAlert()
   **/
    function addError($message, $e) 
    {
        $err = new AMError($message,"AMMain", $e);
    }

  /**
   * Adds an standard message to this page.
   *
   * This function is very similar to addError, except that it adds an
   * standard message and not an error message. It can look to the
   * actual request searching for an frm_ammgs parameter.
   **/
    function addMessage($message) 
    {
        $msg = new AMMessage($message,"AMMain");
    }

  /**
   * Adds an alert box to this page.
   *
   * This function is very similar to addError, except that it adds an
   * alert message and not an error message. It can look to the
   * actual request searching for an frm_amalert parameter.
   *
   * @see AMMain::addError()
   **/
    function addAlert($message)
    {
        $msg = new AMAlert($message);
    }

  /**
   * Sets the path indicator to this page.
   *
   * The path indicatior is a interface unit that
   * display to the user a series os steps being walked
   * and in wich step the user actualy is.
   *
   * @see AMPathIndicator
   * @param AMPathIndicator $path An object describing the actual path.
   **/
    function setPathIndicator(AMPathIndicator $path)
    {
        $this->pathindicator = $path;
    }

  /**
   * Add a new handler to XOAD communication
   *
   * @param String $handler - Name of the class that will be used for XOAD
   */
    public static function addXOADHandler($class, $handlerName) 
    {
        $_SESSION['XOADHandlers'][$class] = $handlerName;
    }

    public static function getXOADHandlers() 
    {
        return $_SESSION['XOADHandlers'];
    }
    
    public function addNotification($notification) 
    {
        $this->page->notifications[] = $notification;
    }
    
    
    public function setRSSFeed($link, $title="") 
    {
    	$this->page->setRSSFeed($link, $title="");
    }
    
    public function __toString() 
    {
        global $_CMAPP,$_language;

   /**
     * Adds the error messages to the page, if there is any. It
     * searchs for the AMErrors intantiated and error messages 
     * passed by the URL with the frm_amerror.
     **/
        $errors = AMError::getErrors();
        if($this->auto_error_report) {
      //search if there is some error passed by the url
            if(!empty($_REQUEST["frm_amerror"])) {
                if(!is_array($_REQUEST["frm_amerror"])) {
                    $_REQUEST["frm_amerror"] = array($_REQUEST["frm_amerror"]);
                }
                
                foreach($_REQUEST["frm_amerror"] as $error) {
                    $errors[] = array("message"=>$_language["error_$error"],
			    "thrower"=>"request");
                }
            }
        }

    /**
     * Adds the messages to the page, if there is any. It
     * searchs for the AMMessage intantiated and messages 
     * passed by the URL with the frm_ammessage.
     **/
        $messages = AMMessage::getMessages();
        if(!empty($_REQUEST["frm_ammsg"])) {
            if(!is_array($_REQUEST["frm_ammsg"])) {
                $_REQUEST["frm_ammsg"] = array($_REQUEST["frm_ammsg"]);
            }
            
            foreach($_REQUEST["frm_ammsg"] as $msg) {
                $messages[] = array("message"=>$_language["msg_$msg"],
			    "thrower"=>"request");
            }
        }

        
        //notification area
        $notices[] = '<div id="notification_area">';
        $notifications = $this->page->getNotifications();
        if(!empty($notifications)) {
            $notices[] = implode('&nbsp;', $notifications);
        }
        $notices[] = '</div>';

        $notices[] = '<div id="erros_area">';
        if(!empty($errors)) {
            $notices[] = '<br />';
            $debug_mode = (int) $_CMAPP['environment']->debug_mode;
            foreach($errors as $num=>$message) {
            	$alBox = new AMAlertBox(AMAlertBox::ERROR,$message['message']);
                $notices[]= $alBox->__toString();
                if($debug_mode) {
                	$alBox2 = new AMAlertBox(AMAlertBox::ERROR, $message['exception']);
                	$notices[] = $alBox2->__toString();
                }
            }
            $notices[] = '<br />';
        }
        $notices[] = '</div>';

        $notices[] = '<div id="alerts_area">';
        if(!empty($this->alerts)) {
            foreach($this->alerts as $num=>$message) {
            	$alBox = new AMAlertBox(AMAlertBox::ALERT,$message);
                $notices[] = $alBox->__toString();
            }
            $notices[] = '<br />';
        }
        $notices[] = '</div>';

        $notices[] = '<div id="messages_area">';
        if(!empty($messages)) {
            foreach($messages as $num=>$message) {
            	$alBox = new AMAlertBox(AMAlertBox::MESSAGE,$message['message']);
                $notices[] = $alBox->__toString();
            }
            $notices[] = '<br />';
        }
        $notices[] = '</div>';

        /*if(!empty($this->pathindicator)) {
            $main_content[] = $this->pathindicator;
        }
		*/
        $this->page->addNotices($notices);
        
        //this div forces the inclusion of an AMTUserinfo in the page
    	//this force the inclusion of the file, so on the fly pages(like comments in diary)
    	//will work .
        $this->page->addPageEnd('<div id="hiddenDIV" style="display:none">');
        $this->page->addPageEnd(new AMTUserinfo(new CMUser));
        $this->page->addPageEnd('</div>');

        //parent::addScript("initDCOM('$_CMAPP[media_url]/rte/blank.htm');");

        AMMain::addXOADHandler('AMEnvSession', 'AMEnvSession');

        $this->page->addPageBegin(XOAD_Utilities::header("$_CMAPP[media_url]/libs/xoad"));

        if($_SESSION['environment']->logged) {
            AMMain::addXOADHandler("AMFinder", "AMFinder");
            $this->page->addPageBegin(self::getScript("var Finder_chatSRC = '$_CMAPP[services_url]/finder/finder_chat.php';"));
            $this->page->addPageEnd(CMHTMLObj::getScript("initEnvironment();"));
            //$this->setOnload("initEnvironment();");
        }
        
        $handlers = AMMain::getXOADHandlers();
        if(!empty($handlers)) {
            foreach($handlers as $class=>$handler) {
                $this->page->addPageBegin(self::getScript("var $handler = ".XOAD_Client::register(new $class)));
            }
        }
        
        return $this->page->__toString();
    }


  //Static function that returns buttons that should be equal in all the system


    public static function getSearchButton($onClick='',$id='searchButton')
    {
        global $_CMAPP,$_language;
        $bt = "";
        if(empty($onClick)) {
            $bt = "<button id='$id' type='submit'>";
        } else {
            $bt = "<button id='$id' type='button' onclick='$onClick'>";
        }
        $bt.='<img src="'.$_CMAPP['images_url'].'/lupa.png" alt="" /> '.$_language['search'].'</button>';

        return $bt;
    }

    public static function getChangePwButton($codeUser)
    {
        global $_CMAPP,$_language;

        $link = $_CMAPP['services_url']."/admin/changepw.php?frm_codUser=$codeUser";
        return '<button class="admin_items button-as-link " type="button" onclick="AM_openURL(\''.$link.'\')">'
        	 . '<img src="'.$_CMAPP['images_url'].'/ico_alt_senha.gif" alt="" /> '.$_language['changepw_button'].'</button>';
    }
    
    
    public static function getChangeStatusButton($codeUser)
    {
        global $_CMAPP,$_language;

        $link = $_CMAPP['services_url']."/admin/changestatus.php?frm_codUser=$codeUser";
        return '<button class="admin_items button-as-link " type="button" onclick="AM_openURL(\''.$link.'\')">'
			 . '<img src="'.$_CMAPP['images_url'].'/ico_alt_status.gif" alt="" /> '.$_language['changestatus_button'].'</button>';
    }
    
    public static function getSendNotificationButton($codeUser)
    {
        global $_CMAPP,$_language;

        $link = $_CMAPP['services_url']."/admin/sendnotif.php?frm_codUser=$codeUser";
        return '<button class="admin_items button-as-link" type="button" onclick="AM_openURL(\''.$link.'\')">'
        	 . '<img src="'.$_CMAPP['images_url'].'/ico_env_notificacao.gif" alt="" /> '.$_language['sendn_button'].'</button>';
    }

    public static function getAddFriendButton($codeUser)
    {
        global $_CMAPP,$_language;
        $link = $_CMAPP['services_url']."/webfolio/userinfo_details.php?frm_codeUser=$codeUser";
        return '<button class="button-as-link" type="button" onclick="AM_openURL(\''.$link.'\')">'
        	 . '<img src="'.$_CMAPP['images_url'].'/ico_webfolio.png" alt="" /> '.$_language['visit_webfolio'].'</button>';
    }


    public static function getViewPageButton($codeUser)
    {
        global $_CMAPP,$_language;
        $link = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=users/user_$codeUser&frm_codeUser=$codeUser";
        return '<button class="button-as-link"type="button" onclick="AM_openURL(\''.$link.'\')">'
        	 . '<img src="'.$_CMAPP['images_url'].'/ico_ver_pagina.gif" alt="" /> '.$_language['visit_page'].'</button>';
    }

    public static function getViewDiaryButton($codeUser)
    {
        global $_CMAPP,$_language;
        $link = "$_CMAPP[services_url]/blog/blog.php?frm_codeUser=$codeUser";
        return '<button class="button-as-link" type="button" onclick="AM_openURL(\''.$link.'\')">'
        	 . '<img src="'.$_CMAPP['images_url'].'/ico_diario.gif" alt="" /> '.$_language['visit_blog'].'</button>';
    }

    public static function getTieButton($codeProject, $codeCommunity)
    {
        global $_CMAPP,$_language;
        $link = "$_CMAPP[services_url]/communities/tieproject.php?frm_codeCommunity=$codeComunity";

        return '<button class="button-as-link" type="button" onclick="AM_openURL(\''.$link.'\')">'
        	 . '<img src="'.$_CMAPP['images_url'].'/ico_diario.gif" alt="" /> '.$_language['tie_project'].'</button>';
    }
}