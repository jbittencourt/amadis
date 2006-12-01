<?php

/**
 * 
 * @ignore
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMPaginas extends AMMain {

  function __construct() {
    global $_CMAPP;
    parent::__construct();


    $this->setMenuSuperior($_CMAPP[images_url]."/bg_psginas.gif",
               $_CMAPP[images_url]."/img_paginas_01.jpg",
               $_CMAPP[images_url]."/img_paginas_sombra.gif");


    $this->setImgId($_CMAPP[imlang_url]."/img_top_paginas.gif");

  }
}