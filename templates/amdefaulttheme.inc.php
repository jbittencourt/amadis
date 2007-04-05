<?php
/**
 * The AMDefaultTheme is the main template in AMADIS.
 *
 * The AMDefaultTheme class is the main template in AMADIS. It creates
 * the default visualization of the environment, adding the menu,
 * the navigation menu (navmenu), and loading the default css and
 * javascript files.
 *
 * @package AMADIS
 * @subpackage Core
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMNavMenu
 **/
class AMDefaultTheme extends AMHTMLPage 
{
    protected $main_menu;
    public $contents;
    protected $slidein;
    protected $navmenu;
    protected $auto_error_report = true;
    protected $pathindicator;
    protected static $XOADHandlers = array();
    protected $alerts;
    public $notifications = array();

    function __construct($theme="") 
    {
        global $_CMAPP;
        parent::__construct();

        $this->requires("tooltip.css",self::MEDIA_CSS);
        $this->requires("lib.js",self::MEDIA_JS);
        $this->requires("finder.js", self::MEDIA_JS);
        $this->requires("finder.css", self::MEDIA_CSS);
        $this->requires("envsession.js", self::MEDIA_JS);
        $this->requires("tipms.css", self::MEDIA_CSS);

        $this->main_menu = new AMMainMenu();

        $this->navmenu = new AMNavMenu();

        $this->leftMargin = 260;

    }

    
    function add($line) 
    {
        $this->contents[] = $line;
    }


    function setImgId($img) 
    {
        $this->imgid = $img;
    }
    
    function setLeftMargin($w) 
    {
        $this->leftMargin = $w;
    }

    public function getNotifications() 
    {
        return $this->notifications;
    }
    
    
    public function __toString() 
    {
        global $_CMAPP,$_language;


    /** 
     * Flush the communicators handlers
     **/

        if(!empty($_SESSION['communicator'])) 
        {
            $this->requires("communicator.php?client");
        }

        $this->setIcon($_CMAPP['images_url']."/ico_pecinha.ico");
        $this->setTitle($_language['amadis']);

    /**
     * Initialize the variables that are defined in the lib.js with
     * the values that were read from the config.xml file by config.inc.php
     **/
        $js = "CMAPP['url']   = '$_CMAPP[url]';\n";
        $js.= "CMAPP['media_url']  = '$_CMAPP[media_url]';\n";
        $js.= "CMAPP['images_url'] = '$_CMAPP[images_url]';\n";
        $js.= "CMAPP['imlang_url'] = '$_CMAPP[imlang_url]';\n";
        $js.= "CMAPP['js_url']     = '$_CMAPP[js_url]';\n";
        $js.= "CMAPP['css_url']    = '$_CMAPP[css_url]';\n";
        $js.= "CMAPP['services_url']  = '$_CMAPP[services_url]';\n";
        $js.= "CMAPP['pages_url']  = '$_CMAPP[pages_url]';\n";
        $js.= "CMAPP['thumbs_url']  = '$_CMAPP[thumbs_url]';\n";


        $this->addPageBegin(CMHTMLObj::getScript($js));

        $this->force_newline=0;

        parent::add('<div id="header">');
    	//logo
		parent::add("	<div class=\"peca-logo\"></div>");
        
		
		/*THIS image asks for atention
		*	TODO
		* 	this image was supposed to be inside CSS, but due to its localized settings, it needs another solution.
		*/
        parent::add("	<div class=\"amadis-logo\"><img src=\"".$_CMAPP['imlang_url']."/logo_amadis.png\" width=\"240\" height=\"68\"/></div>");

		parent::add("	<div class=\"menu\">");
		parent::add($this->main_menu);
		parent::add("	</div>");
        parent::add('</div>');

        
        
        //container -  main area
        parent::add("<div id=\"container\">");
		parent::add("	<div id=\"center\" class=\"column\">");
		
		 if(!empty($this->imgid)) {
            parent::add("<img src=\"".$this->imgid."\" border=0><br>");
            parent::add('<img src="'.$_CMAPP['images_url'].'/dot.gif" height=5>');
        }
        else {
            parent::add("&nbsp;");
        }

        parent::add('<div id="dashed-line"></div>');

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
            parent::add("<br>");
        }

        
        //notification area
        parent::add("<div id='notification_area' align='right'>");
        $notifications = $this->getNotifications();
        if(!empty($notifications)) {
            parent::add(implode("&nbsp;", $notifications));
        }
        
        parent::add("</div>");

        parent::add('<div id="erros_area">');
        if(!empty($errors)) {
            parent::add("<br>");
            $debug_mode = (int) $_CMAPP['environment']->debug_mode;
            foreach($errors as $num=>$message) {
                parent::add(new AMAlertBox(AMAlertBox::ERROR,$message['message']));
                if($debug_mode) parent::add(new AMAlertBox(AMAlertBox::ERROR, $message['exception']));
            }
            parent::add("<br>");
        }
        parent::add('</div>');

        parent::add('<div id="alerts_area">');
        if(!empty($this->alerts)) {
            foreach($this->alerts as $num=>$message) {
                parent::add(new AMAlertBox(AMAlertBox::ALERT,$message));
            }
            parent::add("<br>");
        }
        parent::add('</div>');

        parent::add('<div id="messages_area">');
        if(!empty($messages)) {
            foreach($messages as $num=>$message) {
                parent::add(new AMAlertBox(AMAlertBox::MESSAGE,$message['message']));
            }
            parent::add("<br>");
        }
        parent::add('</div>');

        if(!empty($this->pathindicator)) {
            parent::add($this->pathindicator);
        }

        parent::add($this->contents);
		
		parent::add("	</div>"); // end container -  main area
		
		//left menu
		parent::add("	<div id=\"left\" class=\"column\">");
		parent::add($this->navmenu);
		parent::add("	</div>");
		parent::add("</div>");
		
		//footer
		parent::add("<div id=\"footer-wrapper\">");
		parent::add("	<div id=\"footer\">$_language[amadis]</div>");
		parent::add("</div>");
   
	    //this div forces the inclusion of an AMTUserinfo in the page
    	//this force the inclusion of the file, so on the fly pages(like comments in diary)
    	//will work .
        parent::add("<div id=hiddenDIV style='display:none'>");
        parent::add(new AMTUserinfo(new CMUser));
        parent::add("</div>");

        //parent::addScript("initDCOM('$_CMAPP[media_url]/rte/blank.htm');");

        AMMain::addXOADHandler("AMEnvSession", "AMEnvSession");

        parent::addPageBegin(XOAD_Utilities::header("$_CMAPP[media_url]/libs/xoad"));

        if($_SESSION['environment']->logged) {
            AMMain::addXOADHandler("AMFinder", "AMFinder");
            parent::addPageBegin(self::getScript("var Finder_chatSRC = '$_CMAPP[services_url]/finder/finder_chat.php';"));
            parent::addPageEnd(CMHTMLObj::getScript("initEnvironment();"));
            //$this->setOnload("initEnvironment();");
        }
        
        $handlers = AMMain::getXOADHandlers();
        if(!empty($handlers)) {
            foreach($handlers as $class=>$handler) {
                parent::addPageBegin(self::getScript("var $handler = ".XOAD_Client::register(new $class)));
            }
        }
        
        $html = parent::__toString();
        $html = "  "; //fix error bellow footer 
        AMLog::commit();
        
        return $html;

    }
}