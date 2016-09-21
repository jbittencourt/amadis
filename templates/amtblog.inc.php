<?php
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMTBlog extends AMMain {

	function __construct() {
		global $_CMAPP, $_language;
		parent::__construct('blog');

		$this->requires("diary.css",self::MEDIA_CSS);

		$this->setImgId($_CMAPP['images_url']."/ico_blog.gif");
		$this->setSectionTitle($_language['blog']);

	}
}
