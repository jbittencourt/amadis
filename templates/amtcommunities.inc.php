<?php

/**
 * Community main templates
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Cristiano S. Basso <csbasso@lec.ufrgs.br>
 */

class AMTCommunities extends AMMain {
	

	function __construct() {
		global $_CMAPP;
		parent::__construct("green");

		$this->setImgId($_CMAPP['imlang_url']."/top_comunidades.gif");
		$this->requires("communities.css",CMHTMLObj::MEDIA_CSS);
	}

}
