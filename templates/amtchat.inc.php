<?

/**
 * Chat page template
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMChat
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */
class AMTChat extends AMMain {
  

  function __construct() {
    global $_CMAPP;

    parent::__construct("vermelho");
    
    $this->setImgId($_CMAPP['imlang_url']."/top_chat_amadis.gif"); 
  }
}



?>