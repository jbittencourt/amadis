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
class AMBoxBlog extends AMColorBox {
    
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

    public function __construct(CMContainer $list,$user,$titulo,$imagem=0,$texto="") 
    {
        parent::__construct('', self::COLOR_BOX_PURPLE);

        $this->posts = $list;
        $this->user = $user;
		
        $this->imagem = $imagem;
        $this->texto = $texto;
        $this->titulo = $titulo;
        $this->rsslink =  "blogRSS.php?frm_codeUser=".$user;
        $this->requires("blog.js");
    }

    public function addCabecalho($item) 
    {
        $this->cabecalho[] = $item;
    }

    public function setDate($m,$y) 
    {
        $this->month =$m;
        $this->year = $y;
    }

    public static function getPermanentLink($post) 
    {
        global $_CMAPP;
        return "$_CMAPP[services_url]/blog/blog.php?frm_codePost=$post->codePost#anchor_post_$post->codePost";
        //return "$_CMAPP[services_url]/blog/blog.php?frm_codePost=$post->codePost";
    }
    
    public function __toString() 
    {
        global $_CMAPP,$_language;

        $url = $_CMAPP['images_url'];
	
        //$js = "blog_handler_url = '$_CMAPP[services_url]/diario/comentarios.php';";
        $js.= "blog_delete_link = 'blog.php?frm_action=A_delete&frm_codePost=';";
        $js.= "blog_delete_message = '$_language[post_delete]';";
        $js.= "Blog_preLoadImages('$_CMAPP[images_url]/ico_seta_on_cmnt.gif','$_CMAPP[images_url]/ico_seta_off_cmnt.gif');";

        parent::addScript($js);
		
        
        $box = new AMColorBox('', AMColorBox::COLOR_BOX_INNERBLOG);
        $box->add("<div id='diary-header-picture'>");
        $box->add($this->imagem);
        $box->add("</div>");
		
        /* CALENDAR */
        $calendar = new AMTCalendar($this->month,$this->year);
        if(!empty($_REQUEST['frm_codeUser']))
        $calendar->setMoveLink("$_CMAPP[services_url]/blog/blog.php?frm_codeUser=$this->user&");
        else
        $calendar->setMoveLink("$_CMAPP[services_url]/blog/blog.php?");
        
        $box->add('<div style="margin-left: 5px;float:right;">'.$calendar->__toString().'</div>');
        /* END CALENDAR */
        
        $box->add('<span class="diary-title">'.$this->titulo.'</span>');
		
        $box->add("<acronym title='Really Simple Syndication' style='border: 0px;'>");
        $box->add("<a href='$this->rsslink'><img src='$_CMAPP[images_url]/rss_feed.gif' style='padding-left: 15px;'></a></acronym><br />");

        $box->add("<div id='diary-header-text'>");
        $box->add($this->texto);
        $box->add("</div>");

        $box->add('<div id="diary-header"><br />');
        
        if(empty($_REQUEST['frm_codeUser']) || $_REQUEST['frm_codeUser'] == $_SESSION['user']->codeUser) {
            $box->add(implode("",$this->cabecalho));
        }
        $box->add('</div>'); //diary header;
        
        parent::add($box);
        
		unset($box);
		
		parent::add('<a name="editor"></a><div id="new-post" style="display:none;">');
		
		$requiredFields = array("title","body");
		$form = new AMWSmartForm('AMBlogPost', "cad_post", $_SERVER['PHP_SELF'], $requiredFields, array('codePost'));
		if(!empty($editar)) {
			$form->loadDataFromObject($editar);
		}

		$form->submit_label = $_language['Publicar'];
		$form->cancel_button->setOnClick("Blog.cancelEdit();");
		$form->components['body']->setCols(50);
		$form->components['body']->setRows(5);
		$form->addComponent("frm_action", new CMWHidden("frm_action","A_post"));
		$form->setLabelClass("titpost");
		$form->setRichTextArea("body");
		$form->setDesign(CMWFormEl::WFORMEL_DESIGN_OVER);   // muda as labels do smart form
		
		$postForm = new AMColorBox('', AMColorBox::COLOR_BOX_INNERBLOG);
		$postForm->add($form);
		parent::add($postForm);
		
		parent::add('</div>');
		
       /*
        *posts do diario
        */
		
        if($this->posts->__hasItems()) {
			$i=0;
            foreach($this->posts as $post) {
                $impar = $i % 2;
                $i++;

                $calendar->pointDay(date('d',$post->time), "#anchor_post_$post->codePost");
				
                
                
                if(!$impar) {
                	$box = new AMColorBox('', AMColorBox::COLOR_BOX_PURPLE);	
                } else $box = new AMColorBox('', AMColorBox::COLOR_BOX_INNERBLOG);

                $box->add('<h2 id="post-title-'.$post->codePost.'" class="titpost">'.$post->title.'</h2><span class="datapost"> - '.date("h:i ".$_language['date_format'],$post->time));
                $box->add('<a name="anchor_post_'.$post->codePost.'"></a>');
                $box->add('</span><br /><br />');
                $box->add('<span id="post-content-'.$post->codePost.'" class="diary-text">');
                $box->add(new AMSmileRender($post->body));
                $box->add('</span><br />');
                $box->add('<a class="diary-comment" href="'.self::getPermanentLink($post).'">');
                $box->add($_language['permanent_link'].'</a>');

                //$link_comentarios = "Blog_toogleComments('$post->codePost')";
                $link_comentarios = "Blog.loadComments('$post->codePost');";
				$box->add('<br />');
                if($post->numComments==0) {
                    if($_SESSION['user']) {
                        $box->add("<span class='diary_comment_link'><a class='diary_comment' href='javascript:void(0);' onclick=\"$link_comentarios\">");
                        $box->add("$_language[waiting_comments] <img id='post_comments_$post->codePost' src='$_CMAPP[images_url]/ico_seta_off_cmnt.gif'>");
                        $box->add('</a></span>');
                    }
                } else {
                    $l = "<a class='diary-comment' onclick=\"$link_comentarios\" href='javascript:void(0)'>";
                    $box->add("<span class='diary_comment_link'>");
                    $box->add("$l $_language[comments]<span style='font-size: 130%; color: red;'>($post->numComments)</span> <img id='post_comments_$post->codePost' src='$_CMAPP[images_url]/ico_seta_off_cmnt.gif'></a>");
                    $box->add("</span>");
                }
				
                $tempo_post = time() - $post->time;
                //if ($tempo_post < 86400){
                if($tempo_post < 400000) {
                    if($post->codeUser == $_SESSION['user']->codeUser) {
                        //$link_editar = "post.php?frm_codePost=$post->codePost&frm_action=editar";
                        $link_editar = 'javascript:Blog.editPost('.$post->codePost.');';

                        $box->add('<span align="right"><a class="diary_edit" href="'.$link_editar.'"><img  src="'.$_CMAPP['imlang_url'].'/icon_editar.gif" border="0" align="baseline"></a>');
                        $box->add('&nbsp;&nbsp;&nbsp;<a href="#" onclick="Blog_deletePost('.$post->codePost.')" class="diary_edit"><img src="'.$_CMAPP['imlang_url'].'/icon_excluir.gif" border="0" align="baseline"></a></span>');
                    }
                }
                
                $box->add("<div id='post_$post->codePost' style='display: none;'></div>");
               	parent::add($box);
            }
            
        } else {
        	
            parent::add("<br /><img src='$url/diario_markescuro.gif' alt='' />" );
            parent::add("<span class='datapost'>$_language[blog_empty]</span>");
        }
        
        return parent::__toString();
        
   }

}