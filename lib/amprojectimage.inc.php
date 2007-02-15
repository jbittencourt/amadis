<?php
/**
 * An implementation of AMFixedSizeImage to the Projects
 * 
 * This class implements an representation of project image, setting
 * the maxX and maxY. The getView() function returns an AMTProjectImage.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage AMProject
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFixedSizeImage,AMTProjectImage
 **/
class AMProjectImage extends AMFixedSizeImage implements AMThumbinaiableImage {

  const DEFAULT_IMAGE = 'imagem_default_projetos.jpg';     //the default user image is coded with number 2 in the databse
  public function __construct() {
    parent::__construct();

    $this->maxX = 145;
    $this->maxY = 135;
  }


  /**
   * This function is used to obtain the correct code for an image.
   *
   * This function is used to obtain the correct codeFile for a given
   * image. If the image code of the project is empty, the function
   * returns the default image code.
   *
   * @param AMProject $project The project wich you want to obtain the image code.
   **/
  static public function getImage(AMProject $project) {
    $image = (integer) $project->image;
    if(empty($image)) {
      return self::DEFAULT_IMAGE;
    }
    return $image;
  }


  /**
   * Return the thumbnail for a given project.
   * 
   * @param object $obj The project to return the thumbnail.
   * @param boolean $smallthumb Sets if returned thumbnails is of the small size.
   **/
  static public function getThumb($obj,$smallthumb=false) {
    $image = self::getImage($obj);

    $thumb = new AMProjectThumb($smallthumb);
	$thumb->type = self::DEFAULT_IMAGE;

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
    case CMObj::STATE_DIRTY:
      return new AMTProjectImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_DIRTY_NEW:
      return new AMTProjectImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_PERSISTENT:
	return new AMTProjectImage($this->codeFile);
    case CMObj::STATE_NEW:
      return new AMTProjectImage(self::DEFAULT_IMAGE, AMImageTemplate::METHOD_DEFAULT);
    }
  }


}