<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMHTMLPage extends CMHTMLPage {

  function __construct() {
    parent::__construct("AMADISPage");
    $this->requires("amadis.css",self::MEDIA_CSS);

  }

}

?>