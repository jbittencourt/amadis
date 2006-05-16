<?

/**
 * Paging box
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMPageBox extends CMHTMLObj {
  
  /**
   *Este eh um componente de paginacao dinamico
   *
   *@var numFP numero de intens que serao mostrados por pagina
   */

  private $list, $numPages, $pageId, $requestVars;
  public $init, $final;
  public $numHitsFP, $numItems;
  
  public function __construct($numHitsFP) {

    
    $this->numHitsFP = $numHitsFP;

    //generalizando o calculo da limit query
    //(page*n)+(n-1), page*n
    //    final        init
    if(!isset($_REQUEST['page'])) $_REQUEST['page']='';
    $this->pageId = $_REQUEST['page']*$this->numHitsFP;
    
    $this->init =  $this->pageId;//+1;
    $this->final = $this->pageId+($this->numHitsFP);

    parent::__construct();
    

  }

  public function getInitial() {
    return $this->init;
  }

  public function getFinal() {
    return $this->final;
  }
  
  /*
   * Este metodo serve para adicionar variaveis a URL
   *
   */
  public function addRequestVars($requestVars) {
    $this->requestVars = $requestVars;
  }

  public function __toString() {
    global $_language;

    $this->numPages = ceil($this->numItems/$this->numHitsFP);
    
    //imprime paginacao
    if($this->numPages>0) {
      parent::add("<div align=right>");
      if($_REQUEST['page']!=0) {
	parent::add("<a href=\"$_SERVER[PHP_SELF]?page=0&$this->requestVars\" class=\"cinza\">");
	parent::add("&laquo;$_language[first]</a>");
      }
      
      for($i=0; $i < $this->numPages; $i++) {
	$linkSelected = "<span class=\"error\">".($i+1)."</span>";
	$linkCommum = "<a href=\"$_SERVER[PHP_SELF]?page=$i&$this->requestVars\" class=\"cinza\">".($i+1)."</a>";
	if($i==$_REQUEST['page']) parent::add($linkSelected);
	else parent::add($linkCommum);
	
      }
      if($_REQUEST['page']!=($i-1)) {
	parent::add("<a href=\"$_SERVER[PHP_SELF]?page=".($i-1)."&$this->requestVars\" class=\"cinza\">");
	parent::add("$_language[last] &raquo;</a>");
      }
      
      parent::add("</div>");
    }
    return parent::__toString();
  }
}

?>