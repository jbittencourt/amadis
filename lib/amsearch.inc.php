<?
class AMSearch {

  private $search, $searchtext;
  private $inQuotedString, $params;
  private $tokens = array();
  private $searchFields = array();

  public function __construct($search) {
    $this->search = stripslashes($search);
    $this->searchtext = htmlentities($this->search);
  }    

  /**
   * Esta eh a funcao usada para setar quais campos
   * de uma determinada tabela vao ser usados na busca.
   *
   * Ex.: $fields = array();
   *      $fields[] = "Table.fieldsName";
   *      AMSearch::addSearchFields($fields);
   */
  public function addSearchFields($fields) {
    $this->searchFields = $fields;
  }

  public function __toString() {
    if($this->search != ""){
      $this->params = explode(" ", $this->search);
     
      $tokNum = 0;
     
      $this->tokens[$tokNum] = "";
      foreach($this->params as $param) {
      	if(!isset($this->tokens[$tokNum])) {
	  $this->tokens[$tokNum] = "";
	}
		
	if(ereg("^\"", $param) || ereg("^[+-]\"", $param)) {
	  $this->tokens[$tokNum] .= ereg_replace("\"", "", $param)." ";
	} else {
	  $this->tokens[$tokNum++] = $param;
	}

	if(ereg("\"$", $param)) {
	  $this->tokens[$tokNum] = rtrim($this->tokens[$tokNum]);
	  $tokNum++;
	}
      }

      $this->sqlWhere = "( ";
      
      //clausula where

      foreach($this->tokens as $token) {
	foreach($this->searchFields as $field) {
	  $token = ereg_replace(" $", "", $token);
	  if(ereg("^\\+", $token)) {
	    $tok = ereg_replace("^\\+", "", $token);
	    $this->sqlWhere .= $field." LIKE '%$tok%'";
	    if($field != $this->searchFields[(sizeof($this->searchFields)-1)]) $this->sqlWhere .= " OR ";
	  } else if(ereg("^\\-", $token)) {
	    $tok = ereg_replace("^\\-", "", $token);
	    $this->sqlWhere .= $field." NOT LIKE '%$tok%'";
	    if($field != $this->searchFields[(sizeof($this->searchFields)-1)]) $this->sqlWhere .= " OR ";
	  } else {
	    $this->sqlWhere .= $field." LIKE '%$token%'";
	    if($field != $this->searchFields[(sizeof($this->searchFields)-1)]) $this->sqlWhere .= " OR ";
	  }
	}
	
	if($token != $this->tokens[(sizeof($this->tokens)-1)])
	  $this->sqlWhere .= ") AND (";
	else $this->sqlWhere .= ")";

      }
    }
    return $this->sqlWhere;
  }
}
?>