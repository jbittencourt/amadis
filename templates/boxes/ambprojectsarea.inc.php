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
        $url = $_CMAPP['services_url']."/projetos/listprojects.php";

        /*
        *Buffering html of the box to output screen
        */
        $buffer  = "<table cellspacing=0 cellpadding=0 border=0>\n";
        $buffer .= "<form action=$url method=post name=frm_prjtArea>\n";
        $buffer .= "<tr>\n";
        $buffer .= "<td>\n";
        if($this->index_image) {
            $img = "img_hm_projetos_area.gif";
        } else {
            $img = "img_projetos_area.gif";
        }
        $buffer .= "<img src=\"".$_CMAPP['imlang_url']."/$img\"><br>";
        $buffer .= "<img src=\"".$_CMAPP['images_url']."/dot.gif\" border=\"0\" height=\"7\" width=\"1\"><br>\n";
        $buffer .= "<select onChange=\"document.frm_prjtArea.submit();\" ";
        $buffer .= "name=\"frm_codArea\" style=\"position: relative; top: 0pt;\">\n";
        $buffer .= "<option selected value=\"\">[$_language[select_one]]</option>\n";
        if(!empty($this->areas->items)) {
            foreach($this->areas as $item) {
                $buffer .= "<option value=\"".$item->codeArea."\">".$item->name."</option>\n";
            }
        }
        $buffer .= "</select>\n";
        $buffer .= "<input type=\"hidden\" name=\"list_action\" value=\"A_list_areas\">\n";
        $buffer .= "</font>\n";
        $buffer .= "<br><br><font class=\"textoint\">&raquo; $_language[areas]<br><br>\n";
        $buffer .= "</td>\n";
        $buffer .= "</tr>\n";
        $buffer .= "</form>\n";
        $buffer .= "</table>\n";

        parent::add($buffer);

        return parent::__toString();

    }
}
