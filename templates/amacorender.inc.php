<?

class AMACORender extends CMHTMLObj {

  protected $aco;
  
  public function __construct(CMACO $aco=null) {
    parent::__construct();

    $this->aco = $aco;
  }

  public function __toString() {
    global $_CMAPP;

    $_language = $_CMAPP[i18n]->getTranslationArray("acos");

    if($this->aco==null) {
      $users = $this->aco->listUsersPrivileges();
      $groups = $this->aco->listGroupsPrivileges();
      $parsedGroup = $_SESSION[environment]->getGroupsParents($groups);
    }


    $select_groups = array("users", "projects", "communities");

    parent::add("<P>");

    parent::add(new AMACOListRender);
    

    return parent::__toString();
  }

}


?>