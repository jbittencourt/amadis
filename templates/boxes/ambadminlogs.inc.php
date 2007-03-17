<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminLogs extends AMSimpleBox {

	public function __construct() {
		global $_language;

		parent::__construct($_language['edit_tables']);
	}


	public function __toString() {
		global $_language, $_CMAPP;

		
		if(!isset($_REQUEST['sel_log'])){
			$_REQUEST['sel_log'] = "";
		}


		$conteudo = "<table><tr><td><h3>".$_language['view_logs']."</h3></td></tr>";

		$conteudo .= "<tr><td><form action = $_SERVER[PHP_SELF] method=post>";
		$conteudo .= "<select name=\"sel_log\" id=\"sel_log\">";
		$conteudo .= "<option>".$_language['select']." ...</option>";
		$arDir = scandir($_CMAPP[path]."/log");
			for($i=0; $i<sizeof($arDir); $i++){			
				if($arDir[$i]!="." && $arDir[$i]!= ".."){
					$conteudo .= "	<option value=\"".$arDir[$i]."\">".$arDir[$i]."</option>";
				}
			}
		
		$conteudo .= "</select>";
		$conteudo .= "<input type=button onClick=\"drawLog(AM_getElement('sel_log').value,100);\" value=".$_language['view_log'].">&nbsp;&nbsp;";
		$conteudo .= "<a href=\"javascript:drawLog(AM_getElement('sel_log').value, 5);\">5</a>&nbsp;";
		$conteudo .= "<a href=\"javascript:drawLog(AM_getElement('sel_log').value, 10);\">10</a>&nbsp;";
		$conteudo .= "<a href=\"javascript:drawLog(AM_getElement('sel_log').value, 50);\">50</a>&nbsp;";
		$conteudo .= "</form></td></tr></table><br>";
		$conteudo .= "<div id=\"logger\" style=\"display:none\"></div>";
		parent::add($conteudo);

		return parent::__toString();
	}


}
?>