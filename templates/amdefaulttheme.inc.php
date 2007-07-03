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

	public $mainMenu;
	public $navMenu;
	protected $notices = array();
	
    function __construct($theme="") 
    {
        global $_CMAPP;
        parent::__construct();
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
    
    public function addNotices($notices)
    {
    	$this->notices[] = implode("\n", $notices);
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
        $main_content = array();

        if(!empty($this->imgid)) {
            $section_title = '<img src="'.$this->imgid.'" alt="" /><br />';
        } else {
            $section_title = '&nbsp;';
        }

        $section_title .= '<div id="dashed-line"></div>';
        
        if(!empty($this->pathindicator)) {
            $main_content[] = $this->pathindicator->__toString();
        }
		
        foreach($this->contents as $item) {
			if($item instanceof CMHTMLOBJ) {
				$main_content[] = $item->__toString();
			} else $main_content[] = $item;
		}
        
        $injection = array(
        	'main_menu'=>$this->mainMenu->__toString(),
        	'side_bar'=>$this->navMenu->__toString(),
        	'section_title'=>$section_title,
        	'notices'=>implode("\n", $this->notices),
        	'main_content'=>implode("\n", $main_content)
        );
        
        parent::add(self::loadView($injection, 'default_theme', 'core_templates'));
        
        $html = parent::__toString();
        $html = "  "; //fix error bellow footer 
        AMLog::commit();
        
        return $html;
        
        die();
        
        
		
		/*THIS image asks for atention
		*	TODO
		* 	this image was supposed to be inside CSS, but due to its localized settings, it needs another solution.
		*/
        //parent::add("	<div class=\"amadis-logo\"><img src=\"".$_CMAPP['imlang_url']."/logo_amadis.png\" width=\"240\" height=\"68\"/></div>");


        parent::add($this->contents);
		//parent::add(self::loadView(array('variavel'=>'valor da variavel'), 'color_box', 'core_templates'));		
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
   
        $html = parent::__toString();
        $html = "  "; //fix error bellow footer 
        AMLog::commit();
        
        return $html;

    }
}