<?

/**
 * Abstract class that handles the vizualizion of an image.
 *
 * The image manipulation, persistence and vizualization is always
 * a hard task in a Web Application. This class, along side with AMImage 
 * and AMFoto, aims to help the developer of AMADIS in this process. This
 * specific class handles the vizualization of a image that is (or will be)
 * stored in the database. The class provides a way to abstract 
 * the process of vizualization,
 * in a way that the user class don't need to be woried if the image is
 * already persistent or if it is only a preview of a image that will be
 * stored in the future.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 **/

abstract class  AMImageTemplate extends CMHTMLObj {

  const METHOD_DB=0;
  const METHOD_SESSION=1;
  
  protected $codeArquivo;
  private $imageObj;
  private $method;

  /**
   * Constructor of the class.
   *
   * There are some observations that the developer should be aware when extending this
   * class. Since PHP 5 don't support polimorphism, the value of $code can be interpreted 
   * in two diferent ways. If $method is METHOD_DB, $code is the code of a file in the
   * database, modeled by the class AMArquivo. If the $method is METHOD_SESSION, $code is
   * interpreted as a AMImage.
   *
   * @param mixed $code If method is METHOD_DB, the value is code of the file in the databe. Otherwise is an object of the type AMArquivo
   * @param integer $method The method that should be used to handle the vizualization of the image
   **/
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


  /**
   * Return the URL to the image, considering the method choosen by the user.
   **/
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