<?php

/**
 * Base to load an AMADIS page5~
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMHTMLPage extends CMHTMLPage {

  function __construct() {
    parent::__construct("AMADISPage");
    $this->requires("amadis.css",self::MEDIA_CSS);

  }

}