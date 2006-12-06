<?php
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMTBlog extends AMMain {	

	function __construct() {
		global $_CMAPP;
		parent::__construct("bege");

		$this->requires("diary.css",self::MEDIA_CSS);

		$this->setImgId($_CMAPP['imlang_url']."/top_diario.gif");

	}
}