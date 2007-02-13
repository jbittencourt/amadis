<?php
/**
 * Box widget of the aggregator configuration
 *
 * LICENSE: Licensed under GPL
 *
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    $Id$
 * @since      File available since Release 1.4.0
 * @author     Robson Mendonça <robson@lec.ufrgs.br>
 **/
class AMBAggregator extends CMHTMLObj {
	
	protected $thumb;
	protected $title;
	protected $sources;
	protected $project_id;
	protected $filter;
	
	public function __construct($project_id)
	{
		parent::__construct();
		
		$this->project_id = $project_id;
		$this->requires('aggregator.js', self::MEDIA_JS);
		AMMain::addXOADHandler('AMAgregatorFacade', 'AMAggregator');
	}
	
	/**
 	 * This method set a thumbnail of the aggregator configuration box.
 	 *
 	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 	 * @version 1.0
 	 **/
	public function setThumb($thumb) 
	{
		$this->thumb = $thumb;	
	}
	
	/**
 	 * This method set a message filter to aggregator box.
 	 *
 	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 	 * @version 1.0
 	 **/
	public function setFilter($filter)
	{
		$this->filter = $filter;
	}
	
	/**
 	 * This method set a title to aggregator configuration box.
 	 *
 	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 	 * @version 1.0
 	 **/
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
 	 * This method set a list container of the active members of the project.
 	 *
 	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 	 * @version 1.0
 	 **/
	public function addSources($sources) 
	{
		$this->sources = $sources;
	}
	
	public function __toString()
	{
		global $_CMAPP, $_language;

		parent::add('<!-- corpo do diario -->');

		parent::add("<table cellpadding='0' cellspacing='0' border='0' width='500'>");
		parent::add('<tr>');
		parent::add("<td width='20'><img src='$_CMAPP[images_url]/box_diarioproj_01.gif' width='20' height='18' border='0'></td>");
		parent::add("<td background='$_CMAPP[images_url]/box_diarioproj_bgtop.gif'><img src='imagens/dot.gif' width='20' height='18' border='0'></td>");
		parent::add("<td width='20'><img src='$_CMAPP[images_url]/box_diarioproj_02.gif' width='20' height='18' border='0'></td>");
		parent::add("</tr>");
		parent::add("<tr>");
		parent::add("<td background='$_CMAPP[images_url]/box_diarioproj_bgleft.gif'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='18' border='0'></td>");
		parent::add("<td bgcolor='#B7E1ED' valign='top'>");

		parent::add("<!-- cabeçalho do diario -->");
		parent::add("<table cellpadding='0' cellspacing='0' border='0' width='100%'>");
		parent::add("<tr>");
		parent::add("<!-- obss: a coluna abaixo precisa ter o mesmo widht da imagem de rosto q for enviada dinamicamente pelo usuario (aqui no caso é 87) -->");
		parent::add("<td width='87'class='boxdiarioproj'>");
		parent::add($this->thumb);
		parent::add("</td>");
		parent::add("<td width='20'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("<td valign='top'><font class='titdiarioproj'>$this->title</font><br>");
		parent::add("<font class='txtdiarioproj'>$_language[aggregator_configuration_area]</font><br>");

		parent::add("</td>");
		parent::add("<td width='20'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("</tr>");
		parent::add("</table>");

		parent::add("</td>");
		parent::add("<td background='$_CMAPP[images_url]/box_diarioproj_bgrigth.gif'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='18' border='0'></td>");
		parent::add("</tr>");
		parent::add("<tr>");

		parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_03s.gif' width='20' height='10' border='0'></td>");
		parent::add("<td bgcolor='#B7E1ED'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_04s.gif' width='20' height='10' border='0'></td>");
		parent::add("</tr>");
		parent::add("<!-- fim cabeçalho do diario -->");
		parent::add("<!-- area dos posts -->");
		parent::add("<!-- post sobre área clara (#F1FAFD) -->");
		parent::add("<tr bgcolor='#F1FAFD'><td colspan='3'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='30' border='0'></td></tr>");
		parent::add("<tr bgcolor='#F1FAFD'>");

		parent::add("<td><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("<td valign='top'><img src='$_CMAPP[imlang_url]/img_fontes_rss.gif' align='absmiddle'><br><img src='$_CMAPP[images_url]/dot.gif' width='10' height='7' border='0'><br>");
		parent::add("<table cellpadding='2' cellspacing='0' border='0' width='100%'>");
		parent::add("<tr>");
		parent::add("<td><img src='$_CMAPP[images_url]/dot.gif' width='8' height='10' border='0'></td>");
		parent::add("<td bgcolor='#E1F6FD' align='left' id='sources_list'>");
		
		//Lista de fontes
		if(!empty($this->sources) && $this->sources->__hasItems()) {
			foreach($this->sources as $src) {
				parent::add("<span id='source_$src->codeSource'>");
				if($src->status == AMProjectBlogs::ENUM_STATUS_ENABLE) {
					$img_path = $_CMAPP['images_url'].'/icon_rss_on.gif';
				}else {
					$img_path = $_CMAPP['images_url'].'/icon_rss_off.gif';
				}
				parent::add(" <img src='$img_path' id='status_$src->codeSource' onclick=\"Aggregator_toggleStatus(this.id);\" class='cursor' align='absmiddle'> ");
				if($src->type == AMProjectBlogs::ENUM_TYPE_EXTERNAL) {
					parent::add("<img src='$_CMAPP[images_url]/icon_excluir_agregador.gif' id='delete_$src->codeSource' onclick=\"Aggregator_deleteSource(this.id);\" align='absmiddle'>");
				}
				parent::add("<span class='font_rss'>$src->title</span>");
				parent::add("</span><br>");
									
			}
		}

		parent::add("</td>");
		parent::add("<td align='right'><img src='$_CMAPP[imlang_url]/img_legenda_agregador.gif'></td>");
		parent::add("</tr>");
		parent::add("<tr><td colspan='2'><br><img src='$_CMAPP[imlang_url]/bt_adicionar_rss.gif' onclick='Aggregator_addSource($this->project_id);' class='cursor'><br>");

		parent::add("<div class='box_add_rss'>");
		parent::add("$_language[source_name]: <input id='frm_name' type='text'><br>");
		parent::add("<img src='$_CMAPP[images_url]/dot.gif' width='5' height='4' border='0'><br>");
		parent::add("$_language[rss_link]: <input id='frm_rssLink' type='text'>");
		parent::add("</div>");
		parent::add("<img src='$_CMAPP[images_url]/dot.gif' width='20' height='25' border='0'></td></tr>");
		parent::add("</table>");
		parent::add("</td>");

		parent::add("<td><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("</tr>	");
		parent::add("<!-- fim post sobre área clara (#F1FAFD) -->");	
		parent::add("<!-- post sobre área escura (#DCF0F6) -->");
		parent::add("<tr bgcolor='#DCF0F6'>");
		parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_int_01.gif' width='20' height='10' border='0'></td>");
		parent::add("<td bgcolor='#DCF0F6'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_int_02.gif' width='20' height='10' border='0'></td>");
		parent::add("</tr>");
		parent::add("<tr bgcolor='#DCF0F6'>");

		parent::add("<td background='$_CMAPP[images_url]/box_diarioproj_int_bgleft.gif'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("<td valign='top'><img src='$_CMAPP[imlang_url]/img_palavras_filtro.gif' align='absmiddle'><br><img src='$_CMAPP[images_url]/dot.gif' width='10' height='14' border='0'><br>");
		parent::add("<table cellpadding='0' cellspacing='0' border='0' width='100%'>");
		parent::add("<tr>");
		parent::add("<td align='left' valign='top' class='palavras_filtro' id='filters'>");
		
		//filters
		$count = 1;
		if(!empty($this->filter)) {
			$filters = explode(',', $this->filter);
			foreach($filters as $filter) {
				parent::add("<span id='filter_$count'>");
				parent::add("$filter <img src='$_CMAPP[images_url]/icon_excluir_agregador.gif' onclick=\"Aggregator_deleteFilter($this->project_id, '$filter', $count);\" align='absmiddle'><br>");
				parent::add("</span>");
				$count++;	
			}
		}else parent::add($_language['no_filters'].'<br>');
		
		parent::add("</td>");

		parent::add("<td width='175' valign='top'>");
		//parent::add("<br><a href='#' class='filtro' id='on'> Filtro ATIVO quando encontrar ALGUMA das palavras adicionadas.</a><br><br>");
		//parent::add("<a href='#' class='filtro' id='off'>Filtro ATIVO quando encontrar TODAS as palavras adicionadas.</a><br>");

		parent::add("</td>");

		parent::add("</tr>");
		parent::add("<tr><td colspan='2'><img src='$_CMAPP[images_url]/dot.gif' width='10' height='14' border='0'><br>");
		parent::add("<input id='frm_filter' type='text'><br><img src='$_CMAPP[images_url]/dot.gif' width='10' height='5' border='0'><br>");
		parent::add("<img class='cursor' onclick='Aggregator_addFilter($this->project_id, $count);' src='$_CMAPP[imlang_url]/bt_add_palavra_filtro.gif'></td></tr>");
		parent::add("</table></td>");
		parent::add("<td background='$_CMAPP[images_url]/box_diarioproj_int_bgrigth.gif'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("</tr>	");
		parent::add("<tr bgcolor='#DCF0F6'>");

		parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_int_03.gif' width='20' height='10' border='0'></td>");
		parent::add("<td bgcolor='#DCF0F6'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_int_04.gif' width='20' height='10' border='0'></td>");
		parent::add("</tr>");
		parent::add("<!-- post sobre área escura (#DCF0F6) -->");	
		parent::add("<!-- final area dos posts -->");
		parent::add("<tr>");
		parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_03.gif' width='20' height='20' border='0'></td>");
		parent::add("<td bgcolor='#F1FAFD'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='20' border='0'></td>");

		parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_04.gif' width='20' height='20' border='0'></td>");
		parent::add("</tr>");
		parent::add("</table>");
		parent::add("<!-- fim corpo do diario -->");

		return parent::__toString();
	}
}
?>