<?

class AMBProjectNews extends AMSimpleBox implements CMActionListener {
  
  private $items = array();
  protected $project;
    
  public function __construct($project) {
    global $_CMAPP;
    $this->project = $project;
    $news = $this->project->listNews();
    $this->items = $news[0];

    parent::__construct($_CMAPP['imlang_url']."/img_novidades_projetos.gif");
  }
   
  public function doAction() {
    if(isset($_REQUEST['action'])) {
      if($_REQUEST['action'] == "A_save_news") {
	$news = new AMProjectNews;
	$news->loadDataFromRequest();
	$news->codeUser = $_SESSION['user']->codeUser;
	$news->codeProject = $this->project->codeProject;
	$news->time = time();
	
	try{
	  $news->save();
	  header("Location:$_SERVER[PHP_SELF]?frm_codProjeto=".$this->project->codeProject."&frm_ammsg=save_news_success");
	}catch(CMDBException $e) {
	  header("Location:$_SERVER[PHP_SELF]?frm_codProjeto=".$this->project->codeProject."&frm_amerror=not_save_news");
	}
      }
    }
  }
 

  public function addItem($item) {
    $this->itens[] = $item;
  }

  public function __toString() {
    global $_CMAPP, $_language;
    /*
     *Load a lang file
     */

    /*
     *Buffering html of the box to output screen
     */

    if($this->items->__hasItems()) {
      foreach($this->items as $item) {
	parent::add("&nbsp;<font class=\"textoint\"><b>".$item->title.":</b>&nbsp;");
	parent::add(nl2br($item->text));
	parent::add(' '.$_language['by'].' ');
	parent::add(new AMTUserInfo($item->autor->items[0],AMTUserInfo::LIST_USERNAME));
	parent::add("<br>");
      }
    } else parent::add("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$_language[no_news]");

    parent::add("<br><a href=\"".$_CMAPP['services_url']."/projetos/listprojects.php?list_action=A_list_news&frm_codProjeto=".$this->project->codeProject);
    parent::add("\" class=\"project_news\">&raquo; $_language[more_news]</a>");

    /*
     *Formulario de insersao de noticias
     */
    $group = $this->project->getGroup();
    if($_SESSION['user'] instanceof CMObj) {
      if($group->isMember($_SESSION['user']->codeUser)) {
	parent::add('<br>');
	$box = new AMTShowHide('add_new',"&raquo; $_language[add_news]",  AMTShowHide::HIDE);
	$box->setClass('project_news');
	$form = new AMWSmartForm('AMProjectNews',"form_news", $_SERVER['PHP_SELF'], array("title","text"));
	
	$form->setDesign(CMWFORMEL::WFORMEL_DESIGN_OVER);
	$form->setCancelOff();
	$form->setSpacing(1);
	$form->setLabelClass("texto");
	
	$form->components['title']->setSize(30);
	$form->components['text']->setSize(25,4);
	
	$form->addComponent("action", new CMWHidden("action", "A_save_news"));
	$form->addComponent("codProjeto ", new CMWHidden("frm_codProjeto",$this->project->codeProject));
	$box->add($form);
	parent::add($box);
      }
    }

    return parent::__toString();

      
  }
}

?>
