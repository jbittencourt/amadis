<?

/**
 * An implementation of AMFoto to the Projects
.
 * 
 * This class implements an representation of project image, setting
 * the maxX and maxY. The getView() function returns an AMTProjectImage.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto
 **/
class AMProjImage extends AMFoto {

  private $default_image = 2;     //the default user image is coded with number 2 in the databse


  public function __construct() {
    parent::__construct();

    $this->maxX = 145;
    $this->maxY = 135;
  }

  public function getView() {
    switch($this->state) {
    case CMObj::STATE_PERSISTENT:
      return new AMTProjectImage($this->codeArquivo);
    case CMObj::STATE_DIRTY:
      return new AMTProjectImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_NEW:
      //this workarround must not be removed. empty function don't work with __get 
      
      $d = $this->dados;
      if(empty($d)) {
	return new AMTProjectImage($this->default_image);
      }
      else {
	return new AMTProjectImage($this,AMImageTemplate::METHOD_SESSION);
      }
    }
  }


}



?>