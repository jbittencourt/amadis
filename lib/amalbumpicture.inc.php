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
class AMAlbumPicture extends AMImage implements AMThumbinaiableImage
{

	
  /**
   * Return the thumbnail for a given user.
   * 
   * @param object $obj The user to return the thumbnail.
   * @param boolean $smallthumb Sets if returned thumbnails is of the small size.
   **/
	static public function getThumb($obj,$smallthumb=false)
	{ 
		$image = $obj->codeFile;

		$thumb = new AMAlbumThumb;
		try {
			$thumb->codeFile = $image;
			try {
				$thumb->load();
			} catch(CMDBException $e) {
				Throw $e;
			}
		} catch(CMObjEPropertieValueNotValid $e) {
			Throw $e;
		}

		return $thumb;
	}

	public function getView() {
		switch($this->state) {
			case CMObj::STATE_PERSISTENT:
				return new AMTAlbumImage($this->codeFile . '_' . $this->name, AMImageTemplate::METHOD_DEFAULT);
			case CMObj::STATE_DIRTY:
				return new AMTAlbumImage($this,AMImageTemplate::METHOD_SESSION);
			case CMObj::STATE_DIRTY_NEW:
				return new AMTAlbumImage($this,AMImageTemplate::METHOD_SESSION);
			case CMObj::STATE_NEW:
				return new AMTAlbumImage(self::DEFAULT_IMAGE, AMImageTemplate::METHOD_DEFAULT);
		}
	}
}
