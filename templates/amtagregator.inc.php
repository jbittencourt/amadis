<?php

/**
 * Template to agregator
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMAgregator
 * @version 1.0
 * @author Daniel M. Basso <daniel@basso.inf.br>
 */
class AMTAgregator extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("bege");

    $this->requires("project.css",self::MEDIA_CSS);
    $this->requires("agregator.css",self::MEDIA_CSS);

    $this->setImgId($_CMAPP['imlang_url']."/top_agregador.gif");
    
  }
}