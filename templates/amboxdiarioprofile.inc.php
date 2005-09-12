<?

class AMBoxDiarioProfile extends CMHTMLObj {

  private $imagem_url;
  private $titulo;
  private $cabecalho = array();
  private $comentarios = array();
  private $form;
  protected $my_content;

  public function __construct($imagem_url,$titulo) {
    parent::__construct(0,0) ;
    $this->imagem_url = $imagem_url;
    $this->titulo = $titulo;
  }

  /**
   *
   * Funcao destinada a incluir o cabecalho da pagina.
   * @access public
   * @return string Retorna string do cabecalho
   *
   */
  public function addCabecalho($item) {
    $this->cabecalho[] = $item;
  }

 

  /**
   *
   * Funcao destinada a setar a tabela no padrao CMWSmartForm
   * @access public
   * @param mixed $form Descricao dos campos da tabela
   * @return mixed $form Retorna parametros da tabela a ser criada.
   *
   */
  public function setForm(CMWSmartForm $form) {
    $this->form = $form;
			    		  
  }


  public function add($line) {
    $this->my_content[] = $line;
  }

  public function __toString() {
    global $_CMAPP,$_language;
    

    $url = $_CMAPP[images_url];
    $link_autor = $_CMAPP[service_url];


    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=20 height=20>");
   
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"500\">");
    parent::add("<tr>");
    parent::add("<td width=\"20\"><img src=\"$url/box_diario_01.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("<td background=\"$url/box_diario_bgtop.gif\"><img src=\"$url/dot.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("<td width=\"20\"><img src=\"$url/box_diario_02.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("<td background=\"$url/box_diario_bgleft.gif\"><img src=\"$url/dot.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("<td bgcolor=\"#FAFBFB\" valign=\"top\">");

    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">");
    parent::add("<tr>");
    parent::add("<td width=\"87\"><img src=\"$url/box_diario_logo_postar.gif\"  border=\"0\"></td>");
    parent::add("<td width=\"20\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
    parent::add("<td valign=\"top\"><br><span class=\"diary_title\">$_language[edit_profile]</span><br>");

    parent::add("</td>");
    parent::add("<td width=\"20\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("</table>");
    if(!empty($this->form)) 
      parent::add($this->form);

    if(!empty($this->my_content)) {
      foreach($this->my_content as $line) {
	parent::add($line);
      }
    }

    parent::add("</td>");
    parent::add("<td background=\"$url/box_diario_bgrigth.gif\"><img src=\"$url/dot.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
    parent::add("</tr>");

    parent::add("<tr>");

    parent::add("<td><img src=\"$url/box_diario_05.gif\" width=\"20\" height=\"20\" border=\"0\"></td>");
    parent::add("<td bgcolor=\"#F2F2FE\"><img src=\"$url/dot.gif\" width=\"20\" height=\"20\" border=\"0\"></td>");
    parent::add("<td><img src=\"$url/box_diario_06.gif\" width=\"20\" height=\"20\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("</table>");
   
    parent::add("</td>");
    parent::add("</tr>");
    parent::add("</table>");

    return parent::__toString();
  }
}

?>