<?php
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMTBlogImage extends AMImageTemplate {
	public function __toString() {
		global $_CMAPP;

		$url = $this->getImageURL();
		parent::add("<img src=\"$url\" class=\"boxdiario\">");

		return parent::__toString();
	}

}

