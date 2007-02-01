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

		//top of box
        parent::add("<div class='box_aggregator_top'>");
        parent::add("<img class='left' src='$_CMAPP[images_url]/box_aggregator/box_diarioproj_01.gif'>");
        parent::add("<img class='right' src='$_CMAPP[images_url]/box_aggregator/box_diarioproj_02.gif'>");
        parent::add("</div>");
		//banner box
        parent::add("<div class='box_aggregator_banner'>");
        parent::add("<div class='banner_content'><table widht='100%'><tr><td align='top'>");
        parent::add(new AMTProjectImage($this->image));
        parent::add("</td><td style='padding-left:20px;'valign='top'><span class='titdiarioproj'>$_language[project]: ".$this->title."</span><br>");
        parent::add("<a href='' class='headerdiario'>$_language[select_sources]</a><br>");
        if ($this->isMember) {
            parent::add("<a href='$_CMAPP[services_url]/agregador/edit_sources.php?frm_codeProject=$_REQUEST[frm_codProjeto]'");
            parent::add(" class ='headerdiario'>$_language[config_aggregator]</a><br>");
        }
        
        parent::add("</td></tr></table></div>");
        parent::add("</div>");

		//bottom of banner box
        parent::add("<div class='box_aggregator_banner_bottom'>");
        parent::add("<img class='left' src='$_CMAPP[images_url]/box_aggregator/box_diarioproj_03s.gif'>");
        parent::add("<img class='right' src='$_CMAPP[images_url]/box_aggregator/box_diarioproj_04s.gif'>");
        parent::add("</div>");

		//content box
        parent::add("<div class='box_aggregator_content'>");

		//parent::add("<img src='".$this->posts[image_url]."' width='".$this->posts[image_width]."  height='".$this->posts[image_height]."' border='0'>");
        $par=true;

        foreach($this->posts[items] as $post) {
            if(!ereg($this->getFilter(), $post['description'])) {
                //continue;
            }

            if($par) {
                parent::add("<div class='entry'>");
                parent::add("<img src='$_CMAPP[images_url]/box_aggregator/img_diarioproj_mark.gif' align='absmiddle' >");
                parent::add("<a href='".$post[link]."'><span class='titpost'>".$post[title]."</span></a><span class='datapost'> - ".$post[pubDate]);
                parent::add("</span><br/>");
                parent::add("<span class='txtdiario'>");
                parent::add(html_entity_decode($post[description]));
                parent::add("</span>");
                parent::add("</div>");
            } else {
                parent::add("<div class='box_aggregator_internal_top'>");
                parent::add("<img class='left' src='$_CMAPP[images_url]/box_aggregator/box_diarioproj_int_01.gif'>");
                parent::add("</div>");
                parent::add("<div class='box_aggregator_internal_content'>");
                parent::add("<div class='entry'>");

                parent::add("<a href='".$post[link]."'><img src='$url/diario_markclaro.gif' ");
                parent::add("align='absmiddle' ><span class='titpost'>".$post[title]."</span></a><span class='datapost'> - ".$post[pubDate]);
                parent::add("</span><br/>");
                parent::add("<span class='txtdiario'>");
                parent::add(html_entity_decode($post[description]));
                parent::add("</span>");

                parent::add("</div>");
                parent::add("</div>");
                parent::add("<div class='box_aggregator_internal_bottom'>");
                parent::add("<img class='left' src='$_CMAPP[images_url]/box_aggregator/box_diarioproj_int_03.gif'>");
                parent::add("</div>");
            }
            $par=!$par;
        }
        parent::add("<div class='box_aggregator_footer'>");
        parent::add("<img class='left' src='$_CMAPP[images_url]/box_aggregator/box_diarioproj_03.gif'>");
        parent::add("</div>");
        parent::add("</div><br>");


        return parent::__toString();
    }

}




?>