<?
/**
 * Agregator blogs visualization box
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMAgregator
 * @version 1.0
 * @author Daniel M. Basso <daniel@basso.inf.br>
 */

class AMBoxAgregador extends CMHTMLObj {

    private $image;
    private $title;
    private $isMember;
    private $posts;
    protected $address;
    protected $filters = array();


    public function __construct($list,$address, $isMember) {
        parent::__construct(0,0);

        $this->posts = $list;
        $this->address = $address;
        $this->isMember = $isMember;

        $this->requires("diary.css", self::MEDIA_CSS);
		$this->requires("aggregator.css", self::MEDIA_CSS);
    }
    
    /**
     * This function split $filter in a list of
     * filters
     * 
     * The pattern of the filter is f,fo,foo
     *
     * @param string $filter
     */
    public function addFilter($filter) {
        $filters = split(',', $filter);
        foreach($filters as $filter) {
            $this->filters[] = $filter;
        }
    }
    
    public function getFilter() {
        if(!empty($this->filters)) {
            $filter_str = implode("|", $this->filters);
            return "(".$filter_str.")";
        } else {
            return 0;
        }
    }
    
    public function setHeader(AMProject $proj) {
        $this->image = $proj->image;
        $this->title = $proj->title;
    }
    
    public function __toString() {
        global $_CMAPP,$_language;

		parent::add("<!-- corpo do diario -->");

		parent::add("<table cellpadding='0' cellspacing='0' border='0' width='100%'>");
		parent::add("<tr>");
		parent::add("<td width='20'><img src='$_CMAPP[images_url]/box_diarioproj_01.gif' width='20' height='18' border='0'></td>");
		parent::add("<td background='$_CMAPP[images_url]/box_diarioproj_bgtop.gif'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='18' border='0'></td>");
		parent::add("<td width='20'><img src='$_CMAPP[images_url]/box_diarioproj_02.gif' width='20' height='18' border='0'></td>");
		parent::add("</tr>");
		parent::add("<tr>");
		parent::add("<td background='$_CMAPP[images_url]/box_diarioproj_bgleft.gif'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='18' border='0'></td>");
		parent::add("<td bgcolor='#B7E1ED' valign='top'>");

		parent::add("<!-- cabeçalho do diario -->");
		parent::add("<table cellpadding='0' cellspacing='0' border='0' width='100%'>");
		parent::add("<tr>");
		parent::add("<!-- obss: a coluna abaixo precisa ter o mesmo widht da imagem de rosto q for enviada dinamicamente pelo usuario (aqui no caso é 87) -->");
		if(empty($this->image)) {
			$thumb = new AMTProjectImage(AMProjectImage::DEFAULT_IMAGE, AMImageTemplate::METHOD_DEFAULT);
		}else $thumb = new AMTProjectImage($this->image);
		
		parent::add("<td width='87'><img src='".$thumb->getImageURL()."' width='87' height='94' border='0' class='boxdiarioproj'>");
		
		parent::add("</td>");
		parent::add("<td width='20'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
		parent::add("<td valign='top'><font class='titdiarioproj'>$this->title</font><br>");
		if($this->isMember) {
			parent::add("<a href='$_CMAPP[services_url]/agregator/config_aggregator.php?frm_codeProject=$_REQUEST[frm_codeProject]' class='headerdiario'>&raquo; $_language[edit_source_list].</a><br>");
		}
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
		
		
		
		$par=true;

		if(!empty($this->posts['items'])) {
        	foreach($this->posts['items'] as $post) {
            	if(!ereg($this->getFilter(), $post['description'])) {
                	continue;
            	}

            	if($par) {
                
                	parent::add("<!-- post sobre área clara (#F1FAFD) -->");
					parent::add("<tr bgcolor='#F1FAFD'><td colspan='3'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='30' border='0'></td></tr>");

					parent::add("<tr bgcolor='#F1FAFD'>");
					parent::add("<td><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
					parent::add("<td valign='top'><img src='$_CMAPP[images_url]/img_diarioproj_mark.gif' align='absmiddle'>");
					parent::add("<a href='$post[link]'><span class='titpost'>".$post['title']."</span></a>");
					parent::add("<span class='datapost'>".$post['pubDate']."</span><br><img src='$_CMAPP[images_url]/dot.gif' width='10' height='7' border='0'><br>");
					parent::add("<font class='txtdiarioproj'>".html_entity_decode($post['description']).".</span><br>");
					parent::add("<table cellpadding='0' cellspacing='0' border='0' width='100%'>");
				
					parent::add("<tr><td colspan='2'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='25' border='0'></td></tr>");
					parent::add("</table>");
					parent::add("</td>");
					parent::add("<td><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
					parent::add("</tr>	");
					parent::add("<!-- fim post sobre área clara (#F1FAFD) -->");	
    	        } else {
        	        parent::add("<!-- post sobre área escura (#DCF0F6) -->");
					parent::add("<tr bgcolor='#DCF0F6'>");
					parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_int_01.gif' width='20' height='10' border='0'></td>");
					parent::add("<td bgcolor='#DCF0F6'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
					parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_int_02.gif' width='20' height='10' border='0'></td>");
					parent::add("</tr>");
					parent::add("<tr bgcolor='#DCF0F6'>");
					parent::add("<td background='$_CMAPP[images_url]/box_diarioproj_int_bgleft.gif'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
					parent::add("<td valign='top'><img src='$_CMAPP[images_url]/img_diarioproj_mark.gif' align='absmiddle'>");
					parent::add("<a href='".$post['link']."'><span class='titpost'>".$post['title']."</span></a>");
					parent::add("<span class='datapost'>$post[pubDate]</span><br><img src='$_CMAPP[images_url]/dot.gif' width='10' height='7' border='0'><br>");
	
					parent::add("<font class='txtdiarioproj'>".html_entity_decode($post['description'])."</span><br>");
					parent::add("</td>");
					parent::add("<td  background='$_CMAPP[images_url]/box_diarioproj_int_bgrigth.gif'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
					parent::add("</tr>	");
					parent::add("<tr bgcolor='#DCF0F6'>");
					parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_int_03.gif' width='20' height='10' border='0'></td>");
					parent::add("<td bgcolor='#DCF0F6'><img src='$_CMAPP[images_url]/dot.gif' width='20' height='10' border='0'></td>");
					parent::add("<td><img src='$_CMAPP[images_url]/box_diarioproj_int_04.gif' width='20' height='10' border='0'></td>");
	
					parent::add("</tr>");
					parent::add("<!-- post sobre área escura (#DCF0F6) -->");	
	
    	        }
        	    $par=!$par;
	        }
		}
 		
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