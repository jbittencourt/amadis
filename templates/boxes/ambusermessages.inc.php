<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBUserMessages extends AMColorBox {
  
  	private $cProjects, $cDiary, $cMessages;
  	protected $exception;

  	public function __construct() {
    	global $_CMAPP;
    
    	parent::__construct("$_CMAPP[imlang_url]/box_comentarios.gif",AMColorBox::COLOR_BOX_BEGE);

    	$this->requires("contextmenu.js",self::MEDIA_JS);

    	try {
      		$this->cProjects = $_SESSION['user']->getLastProjectsComments();
      		$this->cDiary = $_SESSION['user']->listLastBlogPostsComments() ;
      		//$this->cMessages = $_SESSION['user']->getLastMessages();
    	} catch(CMDBQueryError $e) {
      		$this->exception = $e;
    	}
   	}

  	public function __toString() {
    	global $_language, $_CMAPP;

    	if(!empty($this->exception)) {
      		parent::add(new AMErrorReport($this->exception,"AMBUserMessages::__toString", AMLog::LOG_WEBFOLIO));
      		return parent::__toString();
    	}

    	parent::add("<script>var contextMenuItems = new Array();</script>");
    	parent::addPageEnd("<div id=\"AMContextMenu\" class=\"skin0\" display:none></div>");
    	parent::addPageEnd("<script>initAMContextMenu();</script>");
    	parent::addPageEnd("<script>initFrameMenu();</script>");
    
		if($this->cProjects->__hasItems()) {
    		$this->__hasItems = true;
      		parent::add("<b>&raquo;$_language[projects]</b><br />");
      		foreach($this->cProjects as $item) {
				$men = $_language[project_comments];
				if($item->numMessages == 1) { 
	  				$men =  $_language[project_comment]; 
				};
				parent::add("<a class=\"new_comments\" href=\"$_CMAPP[services_url]/projects/project.php?frm_codProjeto=$item->codeProject\" class=\"cinza\">");
				parent::add("$item->numMessages $men</a> <b>$item->title</b><br />");
      		}
    	} else $this->__hasItems = false;
    
    	//listing the comments in the Diary
    	if($this->cDiary->__hasItems()) {
      		$this->__hasItems = true;
      		parent::add("<b>&raquo;$_language[blog]</b><br />");

      		foreach($this->cDiary as $item) {
				$men = $_language[blog_comments] ;
				$comments = (integer) $item->numComments;
				if( $comments == 1) { 
	  				$men =  $_language[blog_comment]; 
				};
				parent::add('<a class="new_comments" href="'.AMBoxBlog::getPermanentLink($item).'" class="cinza">');
				parent::add("$item->numComments $men</a> <b>$item->title</b><br />");
      		}
    	} else $this->__hasItems = $this->__hasItems || false;
    
    	if(!$this->__hasItems) parent::add("&nbsp;&nbsp;$_language[no_comments]");
  
  		return parent::__toString();
  	}
}