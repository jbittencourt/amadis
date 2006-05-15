<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */


abstract class  AMImageTemplate extends CMHTMLObj {

  const METHOD_DB=0;
  const METHOD_SESSION=1;
  
  protected $codeArquivo;
  private $imageObj;
  private $method;

  public function __construct($code,$method=self::METHOD_DB) {
    parent::__construct();
    $this->method = $method;
    
    if($method==self::METHOD_DB) {
      $this->codeArquivo = $code;
    }
    elseif($method==self::METHOD_SESSION) {
      $this->imageObj= $code;
    }
    else {
      Throw new AMException("Image render method not recognized.");
    }
  }


  public function getImageURL() {
    global $_CMAPP;
    $url = "";
    switch($this->method) {
    case self::METHOD_DB:
      $url = "$_CMAPP[media_url]/imagewrapper.php?method=db&frm_codeArquivo=".$this->codeArquivo;
      break;
    case self::METHOD_SESSION:
      $rand = rand(0,100000);
      
      $_SESSION['amadis']['imageview'][$rand] =serialize($this->imageObj) ;
      $url = "$_CMAPP[media_url]/imagewrapper.php?method=session&frm_id=$rand";
      break;
    }
    return $url;
  }

}


?>