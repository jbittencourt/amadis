<?php

/**
 * Main listing box
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMUpload
 * @category AMBox
 * @version 0.1
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMUpload, AMBUploadFloatingBox, AMBUploadCreateDiretory, AMBUploadSendFiles
 */

include("cminterface/widgets/cmwjswin.inc.php");

class AMBUpload extends CMHTMLObj {

	private $dir, $theme;

	public function __construct($dir, $theme="") {
		parent::__construct();
		$this->dir = $dir;
		$this->requires("misc.js",self::MEDIA_JS);
		$this->requires("contextmenu.js",self::MEDIA_JS);
		$this->requires("upload.js", self::MEDIA_JS);
		$this->requires("contextmenu.css", self::MEDIA_CSS);

		parent::addPageEnd("<div id=\"AMContextMenu\" display:none></div>");
		parent::addPageEnd(self::getScript("initAMContextMenu();"));

	}

	public function __toString() {
		global $_CMAPP, $urlBase, $dir_pai, $_language;
		if(!isset($popUrlBase)){
			$popUrlBase = "";
		}
		if(!isset($i)){
			$i = "";
		}

		parent::add("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
		parent::add("    <tr>");
		parent::add("      <td bgcolor=\"#8ad2f9\" width=\"10\">");
		parent::add("<img src=\"$_CMAPP[images_url]/box_up_01.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
		parent::add("      <td bgcolor=\"#def0ff\">");
		parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
		parent::add("      <td bgcolor=\"#8ad2f9\" width=\"10\">");
		parent::add("<img src=\"$_CMAPP[images_url]/box_up_02.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
		parent::add("    </tr>");
		parent::add("    <tr>");
		parent::add("      <td bgcolor=\"#def0ff\" width=\"10\">");
		parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
		parent::add("      <td bgcolor=\"#def0ff\">");
		parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"><br>");
		parent::add("      <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width='500'>");
		parent::add("          <tr>");
		parent::add("            <td valign=\"top\"><img src=\"$_CMAPP[imlang_url]/top_meus_arquivos_amadis.gif\"></td>");
		parent::add("            <td><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"55\"></td>");

		parent::add("          </tr>");
		parent::add("      </table>");
		parent::add("      <img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"7\" width=\"55\"><br>");

		parent::add("<table border=\"0\" cellpadding=\"0\" cellspacing=\"2\" width=\"100%\">");
		parent::add("    <tr bgcolor=\"#a8d4ee\">");
		parent::add("      <td valign=\"top\" width=\"10\"><br>");
		parent::add("      </td>");
		parent::add("      <td><img src=\"$_CMAPP[imlang_url]/img_arq_nome.gif\"></td>");
		parent::add("      <td><img src=\"$_CMAPP[imlang_url]/img_arq_data.gif\"></td>");
		parent::add("      <td><img src=\"$_CMAPP[imlang_url]/img_arq_tipo.gif\"></td>");
		parent::add("    </tr>");

		parent::add("<tr bgcolor=\"#eff4ff\">");
		parent::add("      <td valign=\"top\" width=\"10\" class=\"texto\">&nbsp;</td>");
		parent::add("      <td class=\"texto\">");
		parent::add("<a href=\"$urlBase&frm_dir=$dir_pai\">");
		parent::add("<img border=0 src=\"$_CMAPP[images_url]/ico_arq_pastavoltar.gif\" align=\"middle\"></a> ");
		parent::add("<a href=\"$urlBase&frm_dir=$dir_pai\" class=\"cinza\">..</a></td>");
		parent::add("      <td class=\"texto\"></td>");
		parent::add("      <td class=\"texto\"></td>");
		parent::add("    </tr>");

		parent::add("<form id=\"form_upload\" name=\"form_upload\" method=\"post\" action=\"$_SERVER[PHP_SELF]\">");
		//parent::add("<input type=\"text\" name=\"files_download\" value=\"\">");

		/*
		 *Listagem do diretorio
		 */

		if(!empty($this->dir)) {
			$i=0;
			foreach($this->dir as $item) {

				if($i%2) $color="#EFF4FF";
				else $color = "#DEF0FF";
				$i++;
				//#FFCC99 #CCCCFF

				$jsHO = "onmouseover=\"setPointer(this, $i, 'over', '$color', '#FFCC99', '#FFF4CB');\" ";
				$jsHO .= "onmouseout=\"setPointer(this, $i, 'out', '$color', '#FFCC99', '#FFF4CB');\" ";
				$jsHO .= "onmousedown=\"setPointer(this, $i, 'click', '$color', '#FFCC99', '#FFF4CB')\"";

				$js  = "document.getElementById('frm_chk_row_$i').checked = ";
				$js .= "(document.getElementById('frm_chk_row_$i').checked ? false : true);";

				switch($_REQUEST['frm_upload_type']) {
						
					case "project":
						$popUrlBase = self::getScript("popUrlBase = '$_CMAPP[pages_url]/projetos/projeto_".$_REQUEST['frm_codeProjeto'].$_REQUEST['frm_dir']."';");
						break;

					case "user":
						$popUrlBase = self::getScript("popUrlBase = '$_CMAPP[pages_url]/users/user_".$_SESSION['user']->codeUser.$_REQUEST['frm_dir']."';");

						break;
				}

				//id do arquivo
				$id = "Upload_$item[mime]|$item[name]|".$i."|$item[mime]";

				parent::add("<tr $jsHO>");

				parent::add("  <td bgcolor=\"$color\" valign=\"top\" width=\"10\" class=\"texto\">");
				parent::add("    <input name=\"frm_file_$item[name]\" id=\"frm_chk_row_$i\" ");
				//parent::add("    <input onClick=\"$jsC\" name=\"frm_file_$item[name]\" id=\"frm_chk_row_$i\" ");
				parent::add("    value=\"$item[name]\" align=\"center\" type=\"checkbox\">");
				parent::add("  </td>");
				parent::add("  <td bgcolor=\"$color\" onMouseDown='' class=\"texto\">");

				switch($item['mime']) {
					default:
						if(!isset($link)) $link='';
						parent::add("    <a id='$id' onClick=\"$link\" class='curso'>");
						parent::add("    <img id='$id' border=0 src=\"$_CMAPP[images_url]/icon_$item[mime].gif\" align=\"middle\"></a> ");
						parent::add("    <a id='$id' onClick=\"$link\" href=\"#\" class=\"cinza\">$item[name]</a>");
						break;
					case "pasta":
						//parent::add("    <a href=\"$urlBase&frm_dir=$_REQUEST[frm_dir]/$item[name]\">");
						parent::add("<a id='$id'><img id='$id' border=0 src=\"$_CMAPP[images_url]/ico_arq_$item[mime].gif\" align=\"middle\"></a> ");
						parent::add("    <a href=\"$urlBase&frm_dir=$_REQUEST[frm_dir]/$item[name]\" class=\"cinza\">$item[name]</a>");
						break;
				}

				parent::add("  </td>");
				parent::add("  <td bgcolor=\"$color\" onMouseDown=\"$js\" class=\"texto\">$item[time]</td>");
				parent::add("  <td bgcolor=\"$color\" onMouseDown=\"$js\" class=\"texto\">$item[mime_info]</td>");
				parent::add("</tr>");

			}
				
		} else parent::add("<b><i><font class=\"error\">$_language[empty_diretory]</font></i></b>");

		//variaveis do ambiente de upload
		$baseUrl = self::getScript("baseUrl = '$_CMAPP[services_url]/upload/upload.php';");
		parent::addPageBegin($baseUrl);
		parent::addPageBegin($popUrlBase);
		parent::addPageBegin(self::getScript("numItems = '$i';"));
		parent::addPageBegin(self::getScript("dir = '$_REQUEST[frm_dir]';"));
		parent::addPageBegin(self::getScript("upload_type = '$_REQUEST[frm_upload_type]';"));
		if(!isset($_REQUEST['frm_codeProjeto'])) $_REQUEST['frm_codeProjeto'] = '';
		parent::addPageBegin(self::getScript("codeProjeto = '$_REQUEST[frm_codeProjeto]';"));
		//parent::addPageBegin(self::getScript("codeCourse = '$_REQUEST[frm_codeCourse]';"));

		//variaveis de linguagem
		parent::addPageBegin(self::getScript("lang_fields_to_delete = '$_language[fields_to_delete]';"));
		parent::addPageBegin(self::getScript("lang_not_delete_empty_diretory = '$_language[not_delete_empty_diretory]';"));
		parent::addPageBegin(self::getScript("lang_not_selected_files = '$_language[not_selected_files]';"));
		parent::addPageBegin(self::getScript("lang_new_folder_name = '$_language[new_folder_name]';"));
		parent::addPageBegin(self::getScript("lang_overwrite_files = '$_language[overwrite_files]';"));
		parent::addPageBegin(self::getScript("lang_new_file_name = '$_language[new_file_name]';"));
		parent::addPageBegin(self::getScript("lang_invalid_file_name = '$_language[invalid_file_name]';"));
		parent::addPageBegin(self::getScript("lang_invalid_folder_name = '$_language[invalid_folder_name]';"));
		parent::addPageBegin(self::getScript("lang_file_exists = '$_language[file_exists]';"));

		parent::add("</form>");
		parent::add("<tr bgcolor=\"#a8d4ee\">");
		parent::add("      <td colspan=\"4\" valign=\"top\" width=\"10\">");
		parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" height=\"3\" width=\"3\"></td>");
		parent::add("    </tr>");
		parent::add("  </table>");

		parent::add("  <br>");

		//////////////////////////////////
		if(!isset($_REQUEST['frm_page'])) {
			parent::add("<table cellpadding='0' cellspacing='0' border='0' width='100%'>");
			parent::add("   <tr>");
			parent::add("   <td align='left' valign='top'>");
			parent::add("<img class='cursor' onClick='AM_getElement(\"upload_box\").style.visibility=\"visible\"' src='$_CMAPP[imlang_url]/box_up_enviar.gif'><img class='cursor' onClick='UploadDownload(document.form_upload, \"$_REQUEST[frm_dir]\",\"$_REQUEST[frm_upload_type]\",\"$_REQUEST[frm_codeProjeto]\",\"\");' src='$_CMAPP[imlang_url]/box_up_baixar.gif'>");
			parent::add("</td>");
			parent::add("   <td align='right'>");
			parent::add("   <table cellpadding='0' cellspacing='10' border='0' align='right'>");
			parent::add("   <tr>");
			parent::add("   <td valign='top'><img class='cursor' onClick='UploadNewFile();' src='$_CMAPP[imlang_url]/bt_arq_novo.gif'></td>");
			//     parent::add("   <td valign='top'><img src='$_CMAPP[imlang_url]/bt_arq_copiar.gif'></td>");
			//     parent::add("   <td valign='top'><img src='$_CMAPP[imlang_url]/bt_arq_colar.gif'></td>");
			parent::add("      <td valign=\"top\"><img class='cursor' onClick='UploadDelete();'");//document.form_upload, '$i', ");
			//    parent::add("'$_REQUEST[frm_dir]', '$_REQUEST[frm_upload_type]','$_REQUEST[frm_codeProjeto]', '$_REQUEST[frm_codCourse]')\" ");
			parent::add(" src=\"$_CMAPP[imlang_url]/bt_arq_excluir.gif\" border=0></td>");
			parent::add("      <td valign=\"top\"><img class='cursor' onClick=\"UploadNewFolder('$_SERVER[PHP_SELF]', ");
			parent::add("'$_REQUEST[frm_upload_type]', '$_REQUEST[frm_dir]','$_REQUEST[frm_codeProjeto]','');\" ");
			parent::add("src=\"$_CMAPP[imlang_url]/bt_arq_novapasta.gif\" border=0></td>");

			parent::add("   </tr>");
			parent::add("   </table>");
			parent::add("   </td>");
			parent::add("   </tr>");
			parent::add("</table>");
		}
		//////////////////////////////////

		parent::add("      </td>");
		parent::add("      <td bgcolor=\"#def0ff\" width=\"10\">");
		parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
		parent::add("    </tr>");
		parent::add("    <tr>");
		parent::add("      <td bgcolor=\"#8ad2f9\" width=\"10\">");
		parent::add("<img src=\"$_CMAPP[images_url]/box_up_03.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
		parent::add("      <td bgcolor=\"#def0ff\">");
		parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
		parent::add("      <td bgcolor=\"#8ad2f9\" width=\"10\">");
		parent::add("<img src=\"$_CMAPP[images_url]/box_up_04.gif\" border=\"0\" height=\"10\" width=\"10\"></td>");
		parent::add("    </tr>");
		parent::add("</table>");

		return parent::__toString();
	}
}