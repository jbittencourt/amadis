<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

include("cminterface/widgets/cmwjswin.inc.php");

class AMBDirList extends CMHTMLObj {

  private $dir, $theme;

  public function __construct($dir, $theme="") {
    parent::__construct();
    $this->dir = $dir;
    $this->requires("misc.js",self::MEDIA_JS);
    $this->requires("upload.js", self::MEDIA_JS);
    $this->requires("amadis.css", self::MEDIA_CSS);

  }

  public function __toString() {
    global $_CMAPP, $urlBase, $dir_pai, $_language;
    
    parent::add("<table border='0' cellpadding='0' cellspacing='0' width='95%' align='center'>");
    parent::add("    <tr>");
    parent::add("      <td bgcolor='' width='10'>");
    parent::add("<img src='$_CMAPP[images_url]/box_up_01.gif' border='0' height='10' width='10'></td>");
    parent::add("      <td bgcolor='#def0ff'>");
    parent::add("<img src='$_CMAPP[images_url]/dot.gif' border='0' height='10' width='10'></td>");
    parent::add("      <td bgcolor='' width='10'>");
    parent::add("<img src='$_CMAPP[images_url]/box_up_02.gif' border='0' height='10' width='10'></td>");
    parent::add("    </tr>");
    parent::add("    <tr>");
    parent::add("      <td bgcolor='#def0ff' width='10'>");
    parent::add("<img src='$_CMAPP[images_url]/dot.gif' border='0' height='10' width='10'></td>");
    parent::add("      <td bgcolor='#def0ff'>");
    parent::add("<img src='$_CMAPP[images_url]/dot.gif' border='0' height='10' width='10'><br>");
    parent::add("      <table border='0' cellpadding='0' cellspacing='0' width='100%'>");
    parent::add("          <tr>");
    parent::add("            <td valign='top'><img src='$_CMAPP[imlang_url]/top_meus_arquivos_amadis.gif'></td>");
    parent::add("            <td align='right'></td>");

    parent::add("          </tr>");
    parent::add("      </table>");
    parent::add("      <img src='$_CMAPP[images_url]/dot.gif' border='0' height='7' width='55'><br>");
    
    parent::add("<table border='0' cellpadding='0' cellspacing='2' width='100%'>");
    parent::add("    <tr bgcolor='#a8d4ee'>");
    parent::add("      <td valign='top' width='10'><br>");
    parent::add("      </td>");
    parent::add("      <td><img src='$_CMAPP[imlang_url]/img_arq_nome.gif'></td>");
    parent::add("      <td><img src='$_CMAPP[imlang_url]/img_arq_data.gif'></td>");
    parent::add("      <td><img src='$_CMAPP[imlang_url]/img_arq_tipo.gif'></td>");
    parent::add("    </tr>");
    
    parent::add("<tr bgcolor='#eff4ff'>");
    parent::add("      <td valign='top' width='10' class='texto'>&nbsp;</td>");
    parent::add("      <td class='texto'>");
    if($dir_pai != "") {
      if(isset($_REQUEST['frm_codeUser']) && ($_REQUEST['frm_codeUser'] != $_SESSION['user']->codeUser)) {
	$link = "$_CMAPP[services_url]/pages/viewpage.php?frm_page=$dir_pai&frm_codeUser=$_REQUEST[frm_codeUser]";
      } else {
	$link = "$_CMAPP[services_url]/pages/viewpage.php?frm_page=$dir_pai&frm_codeProject=$_REQUEST[frm_codeProject]";
      }
      parent::add("<a target='_top' href='$link' class='cinza'>");
      parent::add("<img border=0 src='$_CMAPP[images_url]/ico_arq_pastavoltar.gif' align='middle'></a> ");
      parent::add("<a target='_top' href='$link' class='cinza'>..</a></td>");
    }
    parent::add("      <td class='texto'></td>");
    parent::add("      <td class='texto'></td>");
    parent::add("    </tr>");
    
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
	
	//id do arquivo
	$id = "Upload_$item[mime]|$item[name]|".$i."|$item[mime]";	
	
	parent::add("<tr $jsHO>");
	parent::add("  <td bgcolor='$color' width='15' onMouseDown='' class='texto'>&nbsp;</td>");
	parent::add("  <td bgcolor='$color' onMouseDown='' class='texto'>");

	switch($item['mime']) {
	default:
	  parent::add("    <a id='$id' href='$urlBase/$item[name]' class='cursor'>");
	  parent::add("    <img id='$id' border=0 src='$_CMAPP[images_url]/icon_$item[mime].gif' align='middle'></a> ");
	  parent::add("    <a id='$id' href='$urlBase/$item[name]' class='cinza'>$item[name]</a>");
	  break;
	case "pasta":
	  parent::add("<a id='$id'><img id='$id' border=0 src='$_CMAPP[images_url]/ico_arq_$item[mime].gif' align='middle'></a> ");
	  parent::add("    <a target='_top' href='$_CMAPP[services_url]/pages/viewpage.php?frm_page=$_REQUEST[frm_page]/$item[name]' class='cinza'>$item[name]</a>");
	  break;
	}
	
	parent::add("  </td>");
	parent::add("  <td bgcolor='$color' onMouseDown=\"$js\" class='texto'>$item[time]</td>");
	parent::add("  <td bgcolor='$color' onMouseDown=\"$js\" class='texto'>$item[mime_info]</td>");
	parent::add("</tr>");

      }
      
    } else parent::add("<b><i><font class='error'>$_language[empty_diretory]</font></i></b>");
    
    parent::add("<tr bgcolor='#a8d4ee'>");
    parent::add("      <td colspan='4' valign='top' width='10'>");
    parent::add("<img src='$_CMAPP[images_url]/dot.gif' height='3' width='3'></td>");
    parent::add("    </tr>");
    parent::add("  </table>");
      
    parent::add("      </td>");
    parent::add("      <td bgcolor='#def0ff' width='10'>");
    parent::add("<img src='$_CMAPP[images_url]/dot.gif' border='0' height='10' width='10'></td>");
    parent::add("    </tr>");
    parent::add("    <tr>");
    parent::add("      <td bgcolor='' width='10'>");
    parent::add("<img src='$_CMAPP[images_url]/box_up_03.gif' border='0' height='10' width='10'></td>");
    parent::add("      <td bgcolor='#def0ff'>");
    parent::add("<img src='$_CMAPP[images_url]/dot.gif' border='0' height='10' width='10'></td>");
    parent::add("      <td bgcolor='' width='10'>");
    parent::add("<img src='$_CMAPP[images_url]/box_up_04.gif' border='0' height='10' width='10'></td>");
    parent::add("    </tr>");
    parent::add("</table>");

    return parent::__toString();
  }
}   

?>