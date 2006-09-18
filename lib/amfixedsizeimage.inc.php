<?
/**
 * This class models a image that is stored in the database and has width and height constraings.
 * 
 * This class should be used when you just to manipulate the image size before
 * store it in the database. If you just want to store the image use directly
 * AMFile. This class resizes the image to match the maxX and maxY
 * properties definitions. The resize finds the best match of size withou distortion
 * of the original image.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMArquivo, AMArquivo, AMUserFoto
 **/
abstract class AMFixedSizeImage extends AMImage {

  protected $maxX = "200";
  protected $maxY = "200";

  /**
   * Returns a visualization of the image.
   *
   * The getview method should returns a CMHTMLObj of this image to
   * be printed in an page. This method should consider if the image
   * is alredy store in the database ($this->state = self::STATE_PERSISTENT ) or
   * not ($this->state = self::STATE_NEW)
   **/
  abstract function getView();



  /**
   * Loads the image from the request.
   *
   * This function loads the image from the $_FILE global variable and
   * resize it to match the $maxX and $maxY constraints.
   *
   * @param String $formname The of tha form that the image was posted.
   **/
  public function loadImageFromRequest($formname) {
    parent::loadImageFromRequest($formname);

    //find the best size
    $x = $this->maxX;
    $y = $this->maxY;

    $this->resize($x,$y);
  }

}



?>