<?
/**
 * Box to create a diretory
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMUpload
 * @category AMBox
 * @version 0.1
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMUpload, AMBUpload, AMBUploadFloatingBox, AMBUploadSendFiles
 */

class AMBUploadCreateDiretory extends CMHTMLObj{
  
  private $upload_type;
  
  public function __construct($upload_type) {
    $this->upload_type = $upload_type;
  }

  public function __toString() {
    global $urlBase;
   
    parent::add("<Br><Br><form action=$_SERVER[PHP_SELF] method=post>");
    parent::add("<input type=hidden name=action value=A_create_dir>");
    parent::add("<input type=hidden name=frm_dir value=$_REQUEST[frm_dir]>");
    parent::add("<input type=hidden name=frm_upload_type value=$_REQUEST[frm_upload_type]>");
    parent::add("<input type=hidden $this->upload_type>");
    parent::add("Nome do diretorio:<input type=text name=frm_dirName>");
    parent::add("<input type=submit name=submit value=Criar>");
    parent::add("</form>");

    return parent::__toString();

  }

}


?>