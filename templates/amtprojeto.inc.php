<?php

/**
 * Base template to projects
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMProject
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMTProjeto extends AMMain {
  

  function __construct() {
    global $_CMAPP, $_language;
    parent::__construct("project");

    $this->requires("project.css",CMHTMLObj::MEDIA_CSS);
    $this->setImgId($_CMAPP['images_url']."/ico_projects.gif");
	$this->setSectionTitle($_language['projects']);
  }
}