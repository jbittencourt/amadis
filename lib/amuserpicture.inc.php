<?php
/**
 * Encapsulates the User Picture.
 *
 * This class implements the picture of a user. It extend the AMFoto superclass that 
 * resize the picture to the scale defined in the constructor of this method. The
 * abstract method getView() return an AMTUserImage that renders an default vizualization
 * of the picture.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFixedSizeImage, AMFile, AMTUserImage
 */
class AMUserPicture extends AMFixedSizeImage implements AMThumbinaiableImage 
{

  const DEFAULT_IMAGE = 'imagem_default_user.gif';     //the default user image is coded with number 1 in the databse
  public function __construct() {
    parent::__construct();

    $this->maxX = 100;
    $this->maxY = 100;
    
  }

  /**
   * This function is used to obtain the correct code for an image.
   *
   * This function is used to obtain the correct codeFile for a given
   * image. If the image code of the user is empty, the function
   * returns the default image code.
   *
   * @param AMUser $user The user wich you want to obtain the image code.
   **/
  static public function getImage($user) {
    $foto = (integer) $user->picture;
    if(empty($foto)) {
      return self::DEFAULT_IMAGE;
    }
    return $foto;
  }

  /**
   * Return the thumbnail for a given user.
   * 
   * @param object $obj The user to return the thumbnail.
   * @param boolean $smallthumb Sets if returned thumbnails is of the small size.
   **/
  static public function getThumb($obj,$smallthumb=false) {

  	$image = self::getImage($obj);

    $thumb = new AMUserThumb($smallthumb);
    $thumb->codeFile = $image;
    try {
      $thumb->load();
    }
    catch(CMDBException $e) {
      Throw $e;
    }

    return $thumb;
  }

  public function getView() {
    switch($this->state) {
    case CMObj::STATE_PERSISTENT:
      return new AMTUserImage($this->codeFile);
    case CMObj::STATE_DIRTY:
      return new AMTUserImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_DIRTY_NEW:
      return new AMTUserImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_NEW:
      return new AMTUserImage(self::DEFAULT_IMAGE, AMImageTemplate::METHOD_DEFAULT);
    }
  }
}