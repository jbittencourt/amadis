<?php

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
 * @see AMFixedSizeImage,AMTCommunityImage
 **/
class AMCommunityImage extends AMFixedSizeImage implements AMThumbinaiableImage {

  const DEFAULT_IMAGE = 3;     //the default user image is coded with number 3 in the databse

  public function __construct() {
    parent::__construct();

    $this->maxX = 145;
    $this->maxY = 135;
  }

  /**
   * This function is used to obtain the correct code for an image.
   *
   * This function is used to obtain the correct codeFile for a given
   * image. If the image code of the community is empty, the function
   * returns the default image code.
   *
   * @param AMCommunities $comm The community wich you want to obtain the image code.
   **/
  static public function getImage($comm) {
    $image = (integer) $comm->image;
    if(empty($image)) {
      return self::DEFAULT_IMAGE;
    }
    return $image;
  }


  /**
   * Return the thumbnail for a give community
   * 
   * @param object $obj The community to return the thumbnail.
   * @param boolean $smallthumb Sets if returned thumbnails is of the small size.
   **/
  static public function getThumb($obj,$smallthumb=false) {
    $image = self::getImage($obj);

    $thumb = new AMCommunityThumb($smallthumb);
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
      return new AMTCommunityImage($this->codeFile);
    case CMObj::STATE_DIRTY:
      return new AMTCommunityImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_DIRTY_NEW:
      return new AMTCommunityImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_NEW:
      return new AMTCommunityImage(self::DEFAULT_IMAGE);
    }
  }


}