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

  private $imagem;
  private $titulo;
  private $cabecalho = array();
  private $posts;
  protected $user;


  public function __construct($list,$user,$titulo,$imagem) {
    parent::__construct(0,0);

    $this->posts = $list;
    $this->user = $user;

    $this->imagem = $imagem;
    $this->titulo = $titulo;
  }

  public function addCabecalho($item) {
    $this->cabecalho[] = $item;
  }

 
  public function __toString() {
    global $_CMAPP,$_language;
    
    $url = $_CMAPP['images_url'];


    parent::add("<img src=\"$url/dot.gif\" width=20 height=20>");
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\"  width=100%>");
    parent::add("<tr>");
    parent::add("<td width=\"20\"><img src=\"$url/box_diario_01.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("<td background=\"$url/box_diario_bgtop.gif\"><img src=\"$url/dot.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("<td width=\"20\"><img src=\"$url/box_diario_02.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("<td background=\"$url/box_diario_bgleft.gif\"><img src=\"$url/dot.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("<td bgcolor=\"#FAFBFB\" valign=\"top\">");
    parent::add("<table cellpadding=\"6\" cellspacing=\"0\" border=\"0\" width=\"100%\">");
    parent::add("<tr>");
    parent::add("<td width=\"80\" align=\"center\">");
    
    parent::add("<img src=\"".$this->posts[image_url]."\" width=\"".
		$this->posts[image_width]."\  height=\"".$this->posts[image_height]."\" border=\"0\">");

    parent::add("</td>");
    parent::add("<td width=\"20\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
    parent::add("<td valign=\"top\"><font class=\"diary_title\">".$this->posts[title]."</font>");

    parent::add("<div id=\"diary_header_text\">");
    parent::add($this->posts[description]);
    parent::add("</div>");
   
    
    parent::add("</td>");
    parent::add("<td width=\"20\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("</table>");

    parent::add("</td>");
    parent::add("<td background=\"$url/box_diario_bgrigth.gif\"><img src=\"$url/dot.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("</tr>");

    parent::add("<tr>");
    parent::add("<td><img src=\"$url/box_diario_03.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
    parent::add("<td bgcolor=\"#FAFBFB\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
    parent::add("<td><img src=\"$url/box_diario_04.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");


    $par=true;

    foreach($this->posts[items] as $post) {
      $par=!$par;
      if ($par) {
	parent::add("<tr>");
	parent::add("<td><img src=\"$url/box_diario_03a.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
	parent::add("<td bgcolor=\"#FAFBFB\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
	parent::add("<td><img src=\"$url/box_diario_04b.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
	parent::add("</tr>");
	parent::add("<tr bgcolor=\"#FAFBFB\">");
	parent::add("<td background=\"$url/box_diario_bgleft.gif\"></td>");
      } else {
	parent::add("<tr bgcolor=\"#F2F2FE\">");
	parent::add("<td></td><td><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td><td></td>");
	parent::add("</tr>");
	parent::add("<tr bgcolor=\"#F2F2FE\">");
	parent::add("<td></td>");
      }



      parent::add("<td valign=\"top\" cellpadding=\"10\"><a href=\"".$post[link]."\"><img src=\"$url/diario_markclaro.gif\" ");
      parent::add("align=\"absmiddle\" ><font class=\"titpost\">".$post[title]."</font></a><font class=\"datapost\"> - ".$post[pubDate]);
      parent::add("</font><br/>");
      parent::add("<font class=\"txtdiario\">".html_entity_decode($post[description])."</font></td>");

      if ($par) {
	parent::add("<td background=\"$url/box_diario_bgrigth.gif\"></td></tr>");
	parent::add("<tr>");
	parent::add("<td><img src=\"$url/box_diario_03.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
	parent::add("<td bgcolor=\"#FAFBFB\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
	parent::add("<td><img src=\"$url/box_diario_04.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
	parent::add("</tr>");
      } else {
	parent::add("<td cellpadding=\"10\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td></tr>");
	parent::add("<tr bgcolor=\"#F2F2FE\">");
	parent::add("<td></td><td><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td><td></td>");
	parent::add("</tr>");
      }

    }
    
    parent::add("<tr>");
    parent::add("<td background=\"$url/box_diario_05.gif\"></td>");
    parent::add("<td bgcolor=\"#F2F2FE\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
    parent::add("<td background=\"$url/box_diario_06.gif\"></td>");
    parent::add("</tr>");
    parent::add("</table><br/>");

    return parent::__toString();
  }

}




?>