<?php

/**
 * @ignore
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMBlog
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMComunidade extends AMMain 
{
    

    function __construct() 
    {
        global $urlimagens, $urlimlang;
        parent::__construct();


        $this->setImgId("$urlimlang/img_tit_comunidade.gif");
    }


}

