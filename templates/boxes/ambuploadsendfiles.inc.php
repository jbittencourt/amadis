<?
/**
 * Box to send files to server
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMUpload
 * @category AMBox
 * @version 0.1
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMUpload, AMBUpload, AMBUploadCreateDiretory, AMBUploadFloatingBox
 */

class AMBUploadSendFiles extends CMHTMLObj {

  private $upload_type;
  
  public function __construct($upload_type) {
    $this->upload_type = $upload_type;
  }

  public function __toString(){
    
    //numfields form
    parent::add("<br><Br><form action=\"".$_SERVER[PHP_SELF]."\" method=\"post\" >");
    parent::add("<input type=hidden name=frm_dir value=$_REQUEST[frm_dir]>");
    parent::add("<input type=hidden name=\"MAX_FILE_SIZE\" value=\"00020\">");
    parent::add("<input type=hidden name=frm_upload_type value=$_REQUEST[frm_upload_type]>");
    parent::add("<input type=hidden $this->upload_type>");
    parent::add("Numero de arquivos:<input type=text name=frm_numFields>");
    parent::add("<input type=submit name=submit value=\"Atualiza\">");
    parent::add("</form>");
    

    //upload form
    parent::add("<Br><form action=\"".$_SERVER[PHP_SELF]."\" method=\"post\" enctype=\"multipart/form-data\">");
    parent::add("<input type=hidden name=action value=A_send_files>");
    parent::add("<input type=hidden name=frm_dir value=$_REQUEST[frm_dir]>");
    parent::add("<input type=hidden name=frm_upload_type value=$_REQUEST[frm_upload_type]>");
    parent::add("<input type=hidden $this->upload_type>");
    
    parent::add("Enviar um arquivo:<br>");
    
    if(!empty($_REQUEST[frm_numFields])) {
      for($i=0; $i<$_REQUEST[frm_numFields]; $i++) {
	parent::add("<input type=file name=frm_file_$i><br>");
      }
    }else parent::add("<input type=file name=frm_file><br>");
    
    parent::add("<br><input type=submit name=submit value=Enviar>");
    parent::add("</form>");
    
    return parent::__toString();
  }
}

?>