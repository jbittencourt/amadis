<?php

/**
 * The AMBoxBlog is a box that list blog entries.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMBlog
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMBoxBlog extends CMHTMLObj 
{
    
    private $imagem;
    private $titulo;
    private $texto;
    private $rsslink;
    private $openCommentsPost;
    private $cabecalho = array();
    private $posts;
    protected $user;

    protected $month;
    protected $year;

    public function __construct(CMContainer $list,$user,$titulo,$imagem=0,$texto="") {
        parent::__construct(0,0);

        $this->posts = $list;
        $this->user = $user;

        $this->imagem = $imagem;
        $this->texto = $texto;
        $this->titulo = $titulo;
        $this->rsslink =  "blogRSS.php?frm_codeUser=".$user;
        $this->requires("blog.js");
    }

    public function addCabecalho($item) {
        $this->cabecalho[] = $item;
    }

    public function setDate($m,$y) {
        $this->month =$m;
        $this->year = $y;
    }

    public static function getPermanentLink($post) {
        global $_CMAPP;
        //return "$_CMAPP[services_url]/blog/blog.php?frm_codePost=$post->codePost#anchor_post_$post->codePost";
        return "$_CMAPP[services_url]/blog/blog.php?frm_codePost=$post->codePost";
    }
    
    public function __toString() {
        global $_CMAPP,$_language;

        $url = $_CMAPP['images_url'];
	
        //$js = "blog_handler_url = '$_CMAPP[services_url]/diario/comentarios.php';";
        $js.= "blog_delete_link = 'blog.php?frm_action=A_delete&frm_codePost=';";
        $js.= "blog_delete_message = '$_language[post_delete]';";
        $js.= "Blog_preLoadImages('$_CMAPP[images_url]/ico_seta_on_cmnt.gif','$_CMAPP[images_url]/ico_seta_off_cmnt.gif');";

        parent::addScript($js);


        parent::add("<img src='$_CMAPP[images_url]/dot.gif' width=20 height=20>");
        parent::add("<table cellpadding='0' cellspacing='0'  width=100%>");
        parent::add("<tr>");
        parent::add("<td width='20'><img src='$url/box_diario_01.gif' width='20' height='18' border='0'></td>");
        parent::add("<td background='$url/box_diario_bgtop.gif'><img src='$url/dot.gif' width='20' height='18' border='0'></td>");
        parent::add("<td width='20'><img src='$url/box_diario_02.gif' width='20' height='18' border='0'></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td background='$url/box_diario_bgleft.gif'><img src='$url/dot.gif' width='20' height='18' border='0'></td>");
        parent::add("<td bgcolor='#FAFBFB' valign='top'>");
        parent::add("<table cellpadding='0' cellspacing='0' border='0' width='100%'>");
        parent::add("<tr>");
        parent::add("<td width='87' valign=top>");

        parent::add("<div id='diary_header_picture'>");
        parent::add($this->imagem);
        parent::add("</div>");

        parent::add("</td>");
        parent::add("<td width='20'><img src='$url/dot.gif' width='20' height='10' border='0'></td>");
        parent::add("<td valign='top'><font class='diary_title'>$this->titulo</font>");
        parent::add("<acronym title='Really Simple Syndication' style='border: 0px;'>");
        parent::add("<a href='$this->rsslink'><img src='$_CMAPP[images_url]/rss_feed.gif' style='padding-left: 15px;'></a></acronym><br />");

        parent::add("<div id='diary_header_text'>");
        parent::add($this->texto);
        parent::add("</div>");
  
        parent::add("<div id='diary_header'>");
        
        if(empty($_REQUEST['frm_codeUser']) || $_REQUEST['frm_codeUser'] == $_SESSION['user']->codeUser) {
            parent::add(implode("\n",$this->cabecalho));
        }
        parent::add("</div>"); //diary header;

        $calendar = new AMTCalendar($this->month,$this->year);
        if(!empty($_REQUEST['frm_codeUser']))
        $calendar->setMoveLink("$_CMAPP[services_url]/blog/blog.php?frm_codeUser=$this->user&");
        else
        $calendar->setMoveLink("$_CMAPP[services_url]/blog/blog.php?");

        parent::add("</td>");
        parent::add("<td width='20'><img src='$url/dot.gif' width='20' height='10' border='0'></td>");
        parent::add("<td width='106'>");
        parent::add($calendar);

        parent::add("</td>");
        parent::add("</tr>");
        parent::add("</table>");


        parent::add("</td>");
        parent::add("<td background='$url/box_diario_bgrigth.gif'><img src='$url/dot.gif' width='20' height='18' border='0'></td>");
        parent::add("</tr>");
        parent::add("<tr>");
        parent::add("<td><img src='$url/box_diario_03.gif' width='20' height='10' border='0'></td>");
        parent::add("<td bgcolor='#FAFBFB'><img src='$url/dot.gif' width='20' height='10' border='0'></td>");
        parent::add("<td><img src='$url/box_diario_04.gif' width='20' height='10' border='0'></td>");
        parent::add("</tr>");

        /*
        *posts do diario
        */
		
        if($this->posts->__hasItems()) {
            $i=0;
            foreach($this->posts as $post) {
                $impar = $i % 2;
                $i++;

                $calendar->pointDay(date('d',$post->time), "#anchor_post_$post->codePost");

                if($impar) {
                    parent::add("<tr bgcolor='#F9F9FF'>");
                    parent::add("<td><img src='$url/box_diario_03a.gif' width='20' height='10' border='0'></td>");
                    parent::add("<td bgcolor='#FAFBFB'><img src='$url/dot.gif' width='20' height='10' border='0'></td>");
                    parent::add("<td><img src='$url/box_diario_04b.gif' width='20' height='10' border='0'></td>");
                    parent::add("</tr>");

                    parent::add("<tr bgcolor='#F9F9FF'>");
                    parent::add("<td background='$url/box_diario_bgleft.gif'><img src='$url/dot.gif' width='20' height='10' border='0'></td>");
                    parent::add("<td valign='top'><img src='$url/diario_markclaro.gif' ");
	 
                }
                else {
                    parent::add("<tr bgcolor='#F2F2FE'>");
                    parent::add("<td><img src='$url/dot.gif' width='20' height='10' border='0'></td>");
                    parent::add("<td valign='top'><img src='$url/diario_markescuro.gif' ");

                }

                
                parent::add("align='absmiddle' ><font class='titpost'>$post->title</font><font class='datapost'> - ".date("h:i ".$_language['date_format'],$post->time));
                parent::add("<a name='anchor_post_$post->codePost' > </a> ");
                parent::add("</font><br><img src='$url/dot.gif' width='10' height='7' border='0'><br>");
                parent::add("<font class='txtdiario'>");
                parent::add(new AMSmileRender($post->body));
                parent::add("</font><br>");
                parent::add("<a class='diary_comment' href='".self::getPermanentLink($post)."'>");
                parent::add($_language['permanent_link'].'</a>');
                parent::add("<table cellpadding='0' cellspacing='0' border='0' width='100%'>");
                parent::add("<tr>");

                $link_comentarios = "Blog_toogleComments('$post->codePost')";

                if($post->numComments==0) {
                    if($_SESSION['user']) {
                        parent::add("<td class='diary_comment_link'><a class='diary_comment' href='javascript:void(0);' onclick=\"$link_comentarios\">");
                        parent::add("$_language[waiting_comments] <img id='post_comments_$post->codePost' src='$_CMAPP[images_url]/ico_seta_off_cmnt.gif'>");
                        parent::add("</a></td>");
                    }
                }
                else {
                    $l = "<a class='diary_comment' onclick=\"$link_comentarios\" href='javascript:void(0)'>";
                    parent::add("<td class='diary_comment_link'>");
                    parent::add("$l $_language[comments]($post->numComments) <img id='post_comments_$post->codePost' src='$_CMAPP[images_url]/ico_seta_off_cmnt.gif'></a>");
                    parent::add("</td>");

	  //this will open the box with the comment after an new comment. But dosent works. :)
// 	  if(($_REQUEST[frm_action]=="A_comentario") && ($_REQUEST[frm_codePost]==$post->codePost)) {
// 	    parent::addPageEnd("<script>Blog_toogleComments('$post->codePost');</script>");
// 	  }
                }

                $tempo_post = time() - $post->time;
                if ($tempo_post < 86400){
                    if($post->codeUser == $_SESSION['user']->codeUser) {
                        $link_editar = "post.php?frm_codePost=$post->codePost&frm_action=editar";

                        parent::add("<td align='right'><a class='diary_edit' href= $link_editar> <img  src='$_CMAPP[imlang_url]/icon_editar.gif' border='0' align='baseline'></a>");
                        parent::add("&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"Blog_deletePost($post->codePost)\" class='diary_edit'><img src='$_CMAPP[imlang_url]/icon_excluir.gif' border='0' align='baseline'></a></td>");
	  

                    }

                }
                
                parent::add("</tr>");

                parent::add("<tr><td colspan='2'>");
                parent::add("<div id='post_$post->codePost' style='display: none;'></div>");

                parent::add("<tr><td colspan='2'><img src='$url/dot.gif' width='20' height='25' border='0'></td></tr>");

                parent::add("</table>");


                parent::add("</td>");


                if($impar) {
                    parent::add("<td  background='$url/box_diario_bgrigth.gif'><img src='$url/dot.gif' width='20' height='10' border='0'></td>");
                    parent::add("</tr>	");
                    parent::add("<tr bgcolor='#F9F9FF'>");
                    parent::add("<td><img src='$url/box_diario_03.gif' width='20' height='10' border='0'></td>");
                    parent::add("<td bgcolor='#FAFBFB'><img src='$url/dot.gif' width='20' height='10' border='0'></td>");
                    parent::add("<td><img src='$url/box_diario_04.gif' width='20' height='10' border='0'></td>");
                }
                else {
                    parent::add("<td><img src='$url/dot.gif' width='20' height='10' border='0'></td>");

                }
                parent::add("</tr>");
	 

                parent::add("<tr bgcolor='#F2F2FE'><td colspan='3'><img src='$url/dot.gif' width='20' height='30' border='0'></td></tr>");

            }
            
        }
        else {
            parent::add("<tr bgcolor='#F2F2FE'>");
            parent::add("<td><img src='$url/dot.gif' width='20' height='10' border='0'></td>");
            parent::add("<td valign='top'><br><img src='$url/diario_markescuro.gif' ");
            parent::add("<span class='datapost'>$_language[blog_empty]</span>");
            parent::add("<td><img src='$url/dot.gif' width='20' height='10' border='0'></td>");


        }

        parent::add("<tr>");
        parent::add("<td><img src='$url/box_diario_05.gif' width='20' height='20' border='0'></td>");
        parent::add("<td bgcolor='#F2F2FE'><img src='$url/dot.gif' width='20' height='20' border='0'></td>");
        parent::add("<td><img src='$url/box_diario_06.gif' width='20' height='20' border='0'></td>");
        parent::add("</tr>");
        parent::add("</table>");
		
        return parent::__toString();
    }

}



