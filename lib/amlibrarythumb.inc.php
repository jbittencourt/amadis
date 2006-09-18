<?

/**  
 * Thumbs to Library
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMLibrary
 * @version 1.0
 * @author Cristiano S Basso <csbasso@lec.ufrgs.br>
 */

class AMLibraryThumb extends AMThumb {

  public function __construct() {
    parent::__construct();
    $this->maxX = 80;
    $this->maxY = 60;
  }

}

?>