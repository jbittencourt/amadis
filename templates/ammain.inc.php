<?

/**
 * The AMMain is the main template in AMADIS.
 *
 * The AMMain class is the main template in AMADIS. It creates
 * the default visualization of the environment, adding the menu,
 * the navigation menu (navmenu), and loading the default css and
 * javascript files.
 *
 * @package AMADIS
 * @subpackage AMTemplates
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMNavMenu
 **/
class AMMain extends AMHTMLPage {
  protected $main_menu;
  protected $contents;
  protected $slidein;
  protected $navmenu;
  protected $auto_error_report = true;
  protected $pathindicator;
  protected $alerts;
  
  function __construct($theme="") {
    global $_CMAPP;
    parent::__construct();

    $this->requires("tooltip.css",self::MEDIA_CSS);
    $this->requires("lib.js",self::MEDIA_JS);
    $this->requires("dcom.js",self::MEDIA_JS);
    $this->requires("finder.js", self::MEDIA_JS);
    $this->requires("finder.css", self::MEDIA_CSS);
    $this->requires("envsession.js", self::MEDIA_JS);
    $this->requires("tipms.css", self::MEDIA_CSS);

    $this->main_menu = new AMMainMenu();

    $this->navmenu = new AMNavMenu();

    $this->leftMargin = 260;

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

    self::addCommunicatorHandler('AMEnvSession');
    self::addCommunicatorHandler('AMFinder');

    //ajaxSync method
    $this->setOnClose("ajaxSync.send();");

  }


  function openNavMenu() {
  }

  public function closeNavMenu() {
  }
  
  function add($line) {
    $this->contents[] = $line;
  }


  function setMenuSuperior($img1,$img2,$img3) {
    $this->tema[0]= $img1;
    $this->tema[1]= $img2;
    $this->tema[2]= $img3;
  }

  function setImgId($img) {
    $this->imgid = $img;
  }

  function setLeftMargin($w) {
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
  public function disableAutoError() {
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
   **/
  function addError($message, $e) {
    $err = new AMError($message,"AMMain", $e);
  }

  /**
   * Adds an standard message to this page.
   *
   * This function is very similar to addError, except that it adds an
   * standard message and not an error message. It can look to the
   * actual request searching for an frm_ammgs parameter.
   **/
  function addMessage($message) {
    $msg = new AMMessage($message,"AMMain");
  }


  function addAlert($message) {
    $this->alerts[] = $message;
  }


  function setPathIndicator(AMPathIndicator $path) {
    $this->pathindicator = $path;
  }

  /** Adds class handler to JPSpan Ajax Framework
   *
   */
  static function addCommunicatorHandler($classHandler) {
    if(!array_search($classHandler, $_SESSION['communicator'])) {
       $_SESSION['communicator'][] = $classHandler;
    }
  }


  public function __toString() {
    global $_CMAPP,$_language;


    $contents = "";
    /**
     *Imprime o centro da caixa
     **/

    /** Flush the communicators handlers
     *
     */
    if(!empty($_SESSION['communicator'])) {
      $this->requires("communicator.php?client");
    }

    $this->setIcon($_CMAPP['images_url']."/ico_pecinha.ico");
    $this->setTitle($_language['amadis']);

    /**
      Initialize the variables that are defined in the lib.js with
       the values that were read from the config.xml file by config.inc.php
     **/
    $js = "CMAPP['url']   = '$_CMAPP[url]';";
    $js.= "CMAPP['media_url']  = '$_CMAPP[media_url]';";
    $js.= "CMAPP['images_url'] = '$_CMAPP[images_url]';";
    $js.= "CMAPP['imlang_url'] = '$_CMAPP[imlang_url]';";
    $js.= "CMAPP['js_url']     = '$_CMAPP[js_url]';";
    $js.= "CMAPP['css_url']    = '$_CMAPP[css_url]';";
    $js.= "CMAPP['services_url']  = '$_CMAPP[services_url]';";
    $js.= "CMAPP['pages_url']  = '$_CMAPP[pages_url]';";
    $js.= "CMAPP['thumbs_url']  = '$_CMAPP[thumbs_url]';";
    
    $this->addPageBegin(CMHTMLObj::getScript($js));

    $this->force_newline=0;


    //logo
    parent::add('<div id="amadis-logo"><img src="'.$_CMAPP['images_url'].'/pecas_amadis.png"></div>');
	

    //header
    parent::add('<div id="amadis-header">');
    parent::add("<img  src=\"".$_CMAPP['images_url']."/img_cabecalho.gif\" border=\"0\" width=\"488\">");    
    parent::add('<div id="amadis-header-menu" >');
    parent::add($this->main_menu);
    parent::add('</div>');
    
    parent::add('<div id="amadis-header-line">');
    parent::add('<img src="'.$_CMAPP['images_url'].'/bg_linhas_lateral.gif">');
    parent::add('</div>');
    parent::add('</div>');

    parent::add('<div id="amadis-global-wrapper">');

    //menu
    parent::add($this->navmenu);

    //parent::add('<div id="content-column">');
    parent::add('<div id="content">');

    if(!empty($this->imgid)) {
      parent::add("<img src=\"".$this->imgid."\" border=0><br>");
      parent::add('<img src="'.$_CMAPP['images_url'].'/dot.gif" height=5>');
    }
    else {
      parent::add("&nbsp;");
    }

    parent::add('<div id="dashed-line"></div>');

    $errors = AMError::getErrors();
    if($this->auto_error_report) {
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

    $messages = AMMessage::getMessages();
    if(!empty($_REQUEST["frm_ammsg"])) {
      if(!is_array($_REQUEST["frm_ammsg"])) {
	$_REQUEST["frm_ammsg"] = array($_REQUEST["frm_ammsg"]);
      }
      ;
      foreach($_REQUEST["frm_ammsg"] as $msg) {
	$messages[] = array("message"=>$_language["msg_$msg"],
			    "thrower"=>"request");
      }
      parent::add("<br>");
    }

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


    //PAGE FOOTER

    parent::add('</div>');//content

    //parent::add('</div>');//content-column

    parent::add('</div>'); //global wrapper

//     parent::add("<div id=\"amadis-footer\">");
//     parent::add('<br>');
//     parent::add("<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" width=\"100%\">");
//     parent::add("<td valign=\"top\" background=\"".$_CMAPP['images_url']."/bg_fundo_cinza.gif\">&nbsp;</td>");
//     parent::add("<td background=\"".$_CMAPP['images_url']."/bg_fundo_cinza.gif\">&nbsp;</td>");
//     parent::add("<td background=\"".$_CMAPP['images_url']."/bg_fundo_cinza.gif\" height=\"35\">&nbsp;</td>");
//     parent::add("<td background=\"".$_CMAPP['images_url']."/bg_linhas_lateral2.gif\" width='10'>");
//     parent::add("<img src=\"".$_CMAPP['images_url']."/dot.gif\" width=\"7\" height=\"1\">");
//     parent::add("</td>");
//     parent::add("<tr><td valign=\"top\" colspan=\"5\" background=\"".$_CMAPP['images_url']."/bg_fundo_cinza2.gif\">");
//     parent::add("<img src=\"".$_CMAPP['images_url']."/dot.gif\" width=\"1\" height=\"5\">");
//     parent::add("</td></tr>");

//     parent::add("</table>");
//     parent::add('</div>');
    
    //this div forces the inclusion of an AMTUserinfo in the page
    //this force the inclusion of the file, so on the fly pages(like comments in diary)
    //will work .
    parent::add("<div id=hiddenDIV style=\"display:none\">");
    parent::add(new AMTUserinfo(new CMUser));
    parent::add("</div>");
    
    parent::addScript("initDCOM('$_CMAPP[media_url]/rte/blank.htm');");
    
    parent::addPageBegin(self::getScript("var AMEnvSession = new amenvsession(AMEnvSessionCallBack);"));
    
    if($_SESSION['environment']->logged) {
      //parent::addPageBegin(self::getScript("var AMFinder = new amfinder(AMFinderCallBack);"));
      //parent::addPageBegin(self::getScript("var Finder_chatSRC = '$_CMAPP[services_url]/finder/finder_chat.php';"));
      
      $this->setOnload("initEnvironment();");
      
    }

    AMError::commit();
    return parent::__toString();

 

  }


  //Static function that returns buttons that should be equal in all the system

  public static function getSearchButton($onClick='',$id='searchButton') {
    global $_CMAPP,$_language;
    $bt = "";
    if(empty($onClick)) {
      $bt = "<button id=\"$id\" type=\"submit\">";
    } else {
      $bt = "<button id=\"$id\" type=\"button\" onClick=\"$onClick\">";      
    }
    $bt.='<img src="'.$_CMAPP['images_url'].'/lupa.png"> '.$_language['search'].'</button>';

    return $bt;
  }

  public static function getChangePwButton($codeUser) {
    global $_CMAPP,$_language;
    
    $link = $_CMAPP['services_url']."/admin/changepw.php?frm_codUser=$codeUser";
    return '<button class="admin_items button-as-link " type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_alt_senha.gif"> '.$_language['changepw_button'].'</button>';
  }
  
  
  public static function getChangeStatusButton($codeUser) {
    global $_CMAPP,$_language;
    
    $link = $_CMAPP['services_url']."/admin/changestatus.php?frm_codUser=$codeUser";
    return '<button class="admin_items button-as-link " type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_alt_status.gif"> '.$_language['changestatus_button'].'</button>';
  }
  
  public static function getSendNotificationButton($codeUser){
    global $_CMAPP,$_language;
      
      $link = $_CMAPP['services_url']."/admin/sendnotif.php?frm_codUser=$codeUser";
      return '<button class="admin_items button-as-link" type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_env_notificacao.gif"> '.$_language['sendn_button'].'</button>';    
  }

  public static function getAddFriendButton($codeUser) {
    global $_CMAPP,$_language;
    $link = $_CMAPP['services_url']."/webfolio/userinfo_details.php?frm_codeUser=$codeUser";
    return '<button class="button-as-link" type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_webfolio.png"> '.$_language['visit_webfolio'].'</button>';
  }


  public static function getViewPageButton($codeUser) {
    global $_CMAPP,$_language;
    $link = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=users/user_$codeUser&frm_codeUser=$codeUser";
    return '<button class="button-as-link"type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_ver_pagina.gif"> '.$_language['visit_page'].'</button>';
  }

  public static function getViewDiaryButton($codeUser) {
    global $_CMAPP,$_language;
    $link = "$_CMAPP[services_url]/diario/diario.php?frm_codeUser=$codeUser";
    return '<button class="button-as-link" type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_diario.gif"> '.$_language['visit_diary'].'</button>';
  }

  public static function getTieButton($codeProject, $codeCommunity) {
    global $_CMAPP,$_language;
    $link = "$_CMAPP[services_url]/communities/tieproject.php?frm_codeCommunity=$codeComunity";
    
    return '<button class="button-as-link" type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_diario.gif"> '.$_language['tie_project'].'</button>';
  }
  //-------------------

}

?>
