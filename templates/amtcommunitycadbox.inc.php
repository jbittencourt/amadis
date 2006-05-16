<?

/**
 * Register community box
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */

class AMTCommunityCadBox extends AMTCadBox {

  public function __construct($title) {
    parent::__construct($title, AMTCadBox::CADBOX_CREATE, AMTCadBox::COMMUNITY_THEME);
  }
 
}



?>