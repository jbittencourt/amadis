<?

class AMBUploadFloatingBox extends CMHTMLObj {
  protected $upload_type;

  public function __construct($theme="") {
    parent::__construct();
  }

  public function __toString() {

    global $_CMAPP, $_language;
    
    $style = "visibility:hidden; position:absolute; top:300px;  left:320px";
    
    parent::add("\n<!--Caixa de upload-->\n\n");
    
    parent::add("<div name=\"upload_box\" id=\"upload_box\" style=\"$style\">");
    
    parent::add("    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">");
    parent::add("       <tr>");
    parent::add("         <td>");
    parent::add("           <img src=\"$_CMAPP[images_url]/up_janela_01.gif\" border=\"0\" height=\"15\" width=\"13\">");
    parent::add("         </td>");
    parent::add("         <td bgcolor=\"#ffdc66\">");
    parent::add("           <img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"15\" width=\"1\"></td>");
    parent::add("         <td>");
    parent::add("           <img src=\"$_CMAPP[images_url]/up_janela_02.gif\" border=\"0\" height=\"15\" width=\"20\">");
    parent::add("         </td>");
    parent::add("       </tr>");
    parent::add("       <tr>");
    parent::add("         <td bgcolor=\"#ffdc66\">");
    parent::add("            <img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"15\" width=\"13\">");
    parent::add("         </td>");
    parent::add("         <td bgcolor=\"#ffdc66\" valign=\"top\">");
    parent::add("           <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"300\">");
    parent::add("             <tr>");
    parent::add("               <td><img src=\"$_CMAPP[images_url]/up_janela_enviar.gif\"></td>");
    parent::add("               <td><a onClick=\"document.getElementById('upload_box').style.visibility='hidden'\">");
    parent::add("                   <img src=\"$_CMAPP[images_url]/up_janela_fechar.gif\" align=\"right\"></a>");
    parent::add("               </td>");
    parent::add("             </tr>");
    parent::add("             <tr><td colspan=\"2\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\"></td></tr>");
    parent::add("             <tr>");
    parent::add("               <td colspan=\"2\" bgcolor=\"#fff4cb\" align=\"center\" valign=\"top\"><br>");

    /*
     *Formulario de upload
     */
    //numfields form
//     parent::add("<form action=\"".$_SERVER[PHP_SELF]."\" method=\"post\" >");
//     parent::add("<input type=hidden name=frm_dir value=$_REQUEST[frm_dir]>");
//     parent::add("<input type=hidden name=\"MAX_FILE_SIZE\" value=\"00020\">");
//     parent::add("<input type=hidden name=frm_upload_type value=$_REQUEST[frm_upload_type]>");
//     parent::add("<input type=hidden $this->upload_type>");
//     parent::add("$_language[number_files_to_send]<input type=text name=frm_numFields>");
//     parent::add("<input type=submit name=submit value=\"Atualiza\">");
//     parent::add("</form>");
    

    //upload form
    parent::add("<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" enctype=\"multipart/form-data\"");
    parent::add(" onSubmit=\"return UploadCheckOverwrite(this, document.form_upload);\">");
    parent::add("<input type=hidden name=frm_codeProjeto value=$_REQUEST[frm_codeProjeto]>");
    //parent::add("<input type=hidden name=frm_codCourse value=$_REQUEST[frm_codCourse]>");
    parent::add("<input type=hidden name=action value=A_send_files>");
    parent::add("<input type=hidden name=frm_dir value=$_REQUEST[frm_dir]>");
    parent::add("<input type=hidden name=frm_upload_type value=$_REQUEST[frm_upload_type]>");
    parent::add("<input type=hidden $this->upload_type>");
    
    parent::add("<span class=texto>$_language[send_files]</span><br>");
    
    if(!empty($_REQUEST['frm_numFields'])) {
      for($i=0; $i<$_REQUEST['frm_numFields']; $i++) {
	parent::add("<input type=file name=frm_file_$i><br>");
      }
    } else {
      for($i = 0; $i < 5; $i++) {
	parent::add("<input type=file name=frm_file_$i><br><br>");
      }
    }
    parent::add("<input type=submit name=submit value=Enviar>");
    parent::add("</form>");

    parent::add("               </td>");
    parent::add("             </tr>");
    parent::add("             <tr><td colspan=\"2\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\"></td></tr>");
    parent::add("           </table>");
    parent::add("         </td>");
    parent::add("         <td background=\"$_CMAPP[images_url]/up_janela_bglt.gif\">");
    parent::add("           <img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"14\" width=\"20\"></td>");
    parent::add("       </tr><tr>");
    parent::add("         <td>");
    parent::add("           <img src=\"$_CMAPP[images_url]/up_janela_03.gif\" border=\"0\" height=\"15\" width=\"13\">");
    parent::add("         </td>");
    parent::add("         <td background=\"$_CMAPP[images_url]/up_janela_bgbt.gif\">");
    parent::add("           <img src=\"$_CMAPP[images_url]/dot.gif\"></td>");
    parent::add("         <td>");
    parent::add("           <img src=\"$_CMAPP[images_url]/up_janela_04.gif\" border=\"0\" height=\"15\" width=\"20\">");
    parent::add("         </td>");
    parent::add("       </tr>");
    parent::add("    </table>");

    parent::add("</div>");
    parent::add("\n<!--final caixa de upload-->\n\n");

    return parent::__toString();

  }
}

?>