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
    global $_CMAPP;
    parent::__construct();

    $this->requires("project.css",CMHTMLObj::MEDIA_CSS);
    $this->setImgId($_CMAPP['imlang_url']."/top_projetos.gif");

  }
}