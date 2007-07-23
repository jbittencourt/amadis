<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectsArea extends CMHTMLObj {

    private $areas = array();
    private $index_image;
    public function __construct($im=false) {
        parent::__construct();
        $this->areas = $_SESSION['environment']->listAreas();

        $this->index_image = $im;
    }
    
    public function __toString() {

        global $_CMAPP, $_language;


        /*
        * URL de submit do form
        */
        $url = $_CMAPP['services_url']."/projects/listprojects.php";

        /*
        *Buffering html of the box to output screen
        */
        $buffer  = "<div>\n";
        $buffer .= "<form action=\"$url\" method=\"post\">\n<div>";
        if($this->index_image) {
            $img = "img_hm_projetos_area.gif";
        } else {
            $img = "img_projetos_area.gif";
        }
        $buffer .= '<img src="'.$_CMAPP['imlang_url'].'/'.$img.'" alt="" /><br />';
        $buffer .= '<br /><span class="textoint">&raquo; '.$_language['areas']."<br /><br />\n";
        $buffer .= '<select onchange="document.frm_prjtArea.submit();" ';
        $buffer .= 'name="frm_codArea" style="position: relative; top: 0pt;">';
        $buffer .= '<option selected="selected">['.$_language['select_one'].']</option>';
        if(!empty($this->areas->items)) {
            foreach($this->areas as $item) {
                $buffer .= '<option value="'.$item->codeArea.'">'.$item->name.'</option>';
            }
        }
        $buffer .= '</select>';
        $buffer .= '<input type="hidden" name="list_action" value="A_list_areas" />';
        $buffer .= '</span>';
        $buffer .= '</div></form>';
        $buffer .= '</div>';

        parent::add($buffer);

        return parent::__toString();

    }
}
