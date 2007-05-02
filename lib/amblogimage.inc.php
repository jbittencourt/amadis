<?php

/**
 * An implementation of AMFoto to the Diary.
 * 
 * This class implements an representation of the diary image, setting
 * the maxX and maxY. The getView() function returns an AMTProjectImage.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto
 **/
class AMBlogImage extends AMFixedSizeImage
{

	public function __construct()
	{
		parent::__construct();

		$this->maxX = 87;
		$this->maxY = 94;
	}
	
	public function getThumb($user,$profile="")
	{
		
		if(!is_a($profile,'AMBlogProfile')) {
			$profile = new AMBlogProfile;
			$profile->codeUser = $user->codeUser;
			try {
				$profile->load();
			} catch(CMDBException $e) {
				return AMUserPicture::getThumb($user);
			}
		}
		$temp = false;
		$temp = $profile->image;
		if(!$temp) return AMUserPicture::getThumb($user);

		$thumb = new AMUserThumb;
		try {
			$thumb->codeFile = $temp;
			$thumb->load();
		} catch(CMDBException $e) {
			Throw $e;
		}

		return $thumb;

	}

	public function getView()
	{
		if($this->state==CMObj::STATE_PERSISTENT) {
			return new AMTBlogImage($this->codeFile);
		}
		else {
			return new AMTBlogImage($this,AMImageTemplate::METHOD_SESSION);
		}
	}


}

