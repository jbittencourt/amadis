<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */
include("cminterface/widgets/cmwjswin.inc.php");

class AMBAvisos extends AMColorBox
{

    public function __construct() {
        global $_CMAPP;
        parent::__construct($_CMAPP['imlang_url']."/box_avisos_amadis.gif",self::COLOR_BOX_BLUE);
    }

    public function __toString() {
        global $_language, $_CMAPP;

        $list = $_SESSION['environment']->listActiveWarnings();

        if(empty($list->items)) {
            parent::add($_language['notices_not_found']);
        }
        else {
            foreach($list as $item) {
                 $win = new CMWJSWin("$_CMAPP[services_url]/warnings/show.php?frm_codeAviso=$item->codeAviso",$item->titulo,300,300);
                parent::add("&raquo; &nbsp; <a href=\"#\" onClick=\"". $win->__toString()."\" class=\"cinza\">$item->titulo</a><br>");
            }
        }

        return parent::__toString();
    }

}



?>