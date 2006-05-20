<?

/**
 * An implementation of AMFoto to the Projects.
 * 
 * This class implements an representation of project image, setting
 * the maxX and maxY. The getView() function returns an AMTProjectImage.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto,AMTCommunityImage
 **/
class AMCommunityImage extends AMFoto {

  const DEFAULT_IMAGE = 3;     //the default user image is coded with number 3 in the databse

  public function __construct() {
    parent::__construct();

    $this->maxX = 145;
    $this->maxY = 135;
  }

  public function getView() {
    switch($this->state) {
    case CMObj::STATE_PERSISTENT:
      return new AMTCommunityImage($this->codeArquivo);
    case CMObj::STATE_DIRTY:
      return new AMTCommunityImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_DIRTY_NEW:
      return new AMTCommunityImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_NEW:
      return new AMTCommunityImage(self::DEFAULT_IMAGE);
    }
  }


}



?>