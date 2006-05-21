<?

class AMBCommunitiesNews extends AMSimpleBox implements CMActionListener {

  const COMMUNITIES_NEWS = 1;
  const COMMUNITY_NEWS = 2;

  private $itens = array();
  private $form, $type;
  public function __construct($type=self::COMMUNITIES_NEWS) {
    global $_CMAPP;
    
    $this->type = $type;

    switch($this->type) {
    case self::COMMUNITIES_NEWS :
      parent::__construct("$_CMAPP[imlang_url]/img_novidades_comunidade.gif");
      $this->itens = $_SESSION[environment]->listLastCommunitiesNews();
      break;
    case self::COMMUNITY_NEWS :
      global $community;
      parent::__construct("$_CMAPP[imlang_url]/img_cm_novidades.gif");
      $this->itens = $community->listNews(); 
    }
  }

  public function doAction () {
    global $_CMAPP, $_language;

    switch($_REQUEST[community_news_action]) {

    default:

      $fields_rec = array("title","text");
      
      //formulary
      $this->form = new AMWSmartForm(AMCommunityNews,"cad_community_news",$_SERVER[PHP_SELF],$fields_rec);
   
      $this->form->setWidgetOrder($fields_rec);
      $this->form->setCancelOff();
      $this->form->setSubmitButtonLabel($_language[send_news]);
      
      $this->form->components[title]->setSize(22);
      $this->form->components[text]->setCols(25);
      $this->form->components[text]->setRows(4);
      
      $this->form->addComponent("codeCommunity", new CMWHidden("frm_codeCommunity", $_REQUEST[frm_codeCommunity]));
      $this->form->addComponent("action",new CMWHidden("community_news_action","A_save_news"));

      break;

    case "A_save_news":

      $news = new AMCommunityNews();
      $news->loadDataFromRequest();
      $news->time = time();
      $news->codeUser = $_SESSION[user]->codeUser;
   
      //save the community
      try {
	$news->save();
      }
      catch(CMDBException $e) {
	header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=save_failure");
      }
  
      $cod = $news->codeCommunity;
      unset($news);

      header("Location: $_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$cod&frm_ammsg=community_news_created");
   
      break;
   
    case "fatal_error":
      //No caso de um erro fatal.
      //A mensagem de erro e exibida pelo proprio template AMMain.
      $cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
      break;
      
    }
  }

  public function __toString() {
    global $_CMAPP, $_language;
    
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\"><br>");

    if($this->itens->__hasItems()) {
      switch($this->type) {
      case self::COMMUNITIES_NEWS :
	foreach($this->itens as $item) {

	  //parent::add("<br>");
	
	  $data = date("d/m/Y",$item->news[0]->time);
	  parent::add("<a class =\"cinza\" href=\"$_CMAPP[services_url]/communities/viewnews.php?frm_codeNews=".$item->news[0]->code."\">");
	  parent::add("\"".$item->news[0]->title."\" ($data) - ".$item->name);
	
	  parent::add("</a><br>");
	}
	break;

      case self::COMMUNITY_NEWS :
	foreach($this->itens as $item) {
	  
	  //parent::add("<br>");
	  
	  $data = date("d/m/Y",$item->time);
	  parent::add("<a class =\"cinza\" href=\"$_CMAPP[services_url]/communities/viewnews.php?frm_codeNews=$item->code\">");
	  parent::add("<b>$item->title</b></a>: ".substr($item->text,0,30)."...<br>");
	  
	  parent::add("($data) - ". $item->users[0]->name ."<br>");
	}
	parent::add($this->form);
	break;
      }
    }else parent::add("<br>".$_language[without_communities_news]);
    
    
    return parent::__toString();

  }
}

?>