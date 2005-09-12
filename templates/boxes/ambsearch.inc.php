<?

class AMBSearch extends AMColorBox  {

  private static $_inicialized = false;
  private $search;
  public $action;

  public function __construct($action,$title,$theme) {
    parent::__construct($title,$theme);
    $this->requires("search.js",CMHTMLObj::MEDIA_JS);
    $this->action = $action;
  }
  

  public function __toString() {
    global $_language,$_CMAPP;

    if(!self::$_inicialized) {
      parent::addPageEnd(CMHTMLObj::getScript(" message_empty_search = '$_language[empty_search]'; "));
      self::$_inicialized = true;
    }


    $t = "<table cellspacing=\"0\" cellpading=\"0\" border=\"0\" width=\"100%\">";
    $form  = "<form name=\"search_form\" action=\"$this->action\" method=\"post\"";
    $form .= " onSubmit=\"return Search_validateForm(this.elements['frm_search'].value);\">";
    $form .= "<input type=\"hidden\" name=\"search_action\" value=\"listing\">";
    $form .= "<input type=\"hidden\" name=\"frm_action\" value=\"search_result\">";
    $form .= "$t<tr><td colspan=2><input type=\"text\" name=\"frm_search\"></td></tr>";
    $form .= "<tr><td class=textobox width=50%>";
    
    $form .= "<td>".AMMain::getSearchButton()."</td></tr>";
    $form .= "<input type=\"hidden\" name=\"list_action\" value=\"search_communities\">";
    
    $form .= "</form></table>";
    
    parent::add($form);
    
    return parent::__toString();
  }
}
?>