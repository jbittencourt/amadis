<?

/**
 * Render smiles in the messages
 * Load all smiles as a config.xml file
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */

class AMSmileRender extends CMHTMLObj {

  private static $_smilies = array();
  private static $_configured=0;
  private $text;

  public function __construct($text) {
    global $_conf,$_CMAPP;
    parent::__construct();
    $this->text = $text;
    
    if(!self::$_configured) {
      $node = $_conf->app->interface->smilies;

      foreach($node->smile as $smile) {
	$temp = $_CMAPP['images_url']."/smilies/".(string) $smile['image'];
	self::$_smilies[(string) $smile['key']] = "<img src=\"$temp\">";
      }
      self::$_configured = 1;
    }

  }
  
  public function __toString() {
    parent::add(strtr($this->text, self::$_smilies));
    return parent::__toString();
  }

}
