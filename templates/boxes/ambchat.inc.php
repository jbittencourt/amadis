<?
include("cminterface/widgets/cmwjswin.inc.php");
class AMBChat extends CMHTMLObj {
  
  private $salasAbertas;

  public function addInfo($info,$cod){
    $this->info = $info;
    $this->cod = $cod;
  }

  public function addMsg($condicao,$link){
    $this->condicao = $condicao;
    $this->link = $link;
  }

  public function addForm($formulario){
    $this->form = $formulario;
  }  
  public function addSalasAbertas($salas) {
    $this->salasAbertas = $salas;
  }
  public function addChatsFuturos($futuros,$tag,$tag2){
    $this->agendados = $futuros;
    $this->texto = $tag;
    $this->texto2 = $tag2;
  }
  
  public function __toString() {
    
    global $_CMAPP,$_language;
    $texto[0] = $_language[no_users_inside];
    $texto[1] = $_language[one_user_inside];
    $texto[2] = $_language[users_inside];

    
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"530\">");
    parent::add("<tr bgcolor=\"#E1F7F9\">");
    parent::add("   <td width=\"10\"><img src=\"$_CMAPP[images_url]/box_chat_cl1.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("   <td colspan=\"2\" class=\"txttitchat\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("   <td align=\"right\"><img src=\"$_CMAPP[images_url]/box_chat_cl2.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<tr bgcolor=\"#E1F7F9\">");
    parent::add("   <td width=\"10\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("   <td valign=\"top\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"><br>");

    //decide a imagem titulo do chat
    if($_SESSION[tipo_cod]->tipo=="Projeto"){
      parent::add(new AMTProjectImage($this->info->items[$this->cod]->image));
    }
    if($_SESSION[tipo_cod]->tipo=="Comunidade"){
      parent::add(new AMTCommunityImage($this->info->items[$this->cod]->image));
    }
    
    parent::add(" </td>");
    parent::add("   <td valign=\"top\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"7\" height=\"7\" border=\"0\"><br>");
    parent::add("       <table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">");
    parent::add("       <tr>");

    
    //Busca nome do curso ou projeto e printa ele
    
    if($_SESSION[tipo_cod]->tipo=="Projeto"){
      parent::add("           <td colspan=\"2\" class=\"titchat\">".$this->info->items[$this->cod]->title);
    }
    if($_SESSION[tipo_cod]->tipo=="Curso"){
       parent::add("           <td colspan=\"2\" class=\"titchat\">".$this->info->items[$this->cod]->nome);
    }
    if($_SESSION[tipo_cod]->tipo=="Comunidade"){
       parent::add("           <td colspan=\"2\" class=\"titchat\">".$this->info->items[$this->cod]->name);
    }
    parent::add("               <img src=\"$_CMAPP[images_url]/dot.gif\" width=\"7\" height=\"7\" border=\"0\">");
    parent::add("           </td>");
    parent::add("       </tr>");
    parent::add("       <tr>");
    parent::add("          <td>");
    //formulario
    
    
    parent::add($this->form);
    
    parent::add("          </td>");
    parent::add("      </tr>");
    parent::add("      </table>");
    parent::add("   </td>");
    parent::add("   <td width=\"10\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("   <td><img src=\"$_CMAPP[images_url]/box_chat_cl3.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    
    parent::add("   <td colspan=\"2\" bgcolor=\"#E1F7F9\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("   <td><img src=\"$_CMAPP[images_url]/box_chat_cl4.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("</table>");
    parent::add("<br><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"20\" height=\"15\" border=\"0\"><br>");
    

    //decide a imagem de salas abertas!
    if ($_SESSION[tipo_cod]->tipo == "Projeto" ){
      parent::add("<img src=\"$_CMAPP[images_url]/pt-br/box_chat_salas_abertas.gif\"><br><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"8\" border=\"0\"><br>");
    }
    else{
       parent::add("<img src=\"$_CMAPP[images_url]/pt-br/box_chat_cursos_salas_abert.gif\"><br><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"8\" border=\"0\"><br>");
    }
    parent::add("<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width=\"100%\">");
    parent::add("<tr>");
    //lista salas abertas    
    if (!empty($this->salasAbertas->items)){
      foreach($this->salasAbertas as $sala){
	parent::add("<tr>");
	parent::add("<td width=\"31\"><img src=\"$_CMAPP[images_url]/bt_chat_balao.gif\" border=\"0\" height=\"15\" width=\"31\"></td>");
	$flag=0;
	$link = new CMWJSWin("chatroom.php?frm_codSala=".$sala->codSala."&conexao=$flag",$sala->codSala,540,620);
	$link->setResizeOff();
	$num = $sala->pessoasNaSala();
	switch($num){
	case 0:
	  $i = $texto[0];
	  break;
	case 1:
	  $i = $texto[1];
	  break;
	default:
	  $i = $num.$texto[2];
	  break;
	}
	$str = "<td align=\"left\"><a class=\"linkchat\" href=\"#\"  onClick=\"".$link->__toString()."\"> &raquo; ";
	$str .= "<b>".$sala->nomSala."</b> - ".$i." </a></td>";
	parent::add($str);
	parent::add("</tr>");
      }
    }
    
    else{
    if ($this->condicao=="sim"){
	parent::add("<tr><td> ".$this->link."</td</tr>");
      }
      else{
	parent::add("<tr><td class=\"txtchat\" ><b>$this->texto2</b></td></tr>");
      }
    }
      
    //final da lista de salas abertas

    parent::add("</tr>");
    parent::add("</table>");
    parent::add("<br><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"20\" height=\"15\" border=\"0\"><br>");
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100\">");
    parent::add("<tr>");
    parent::add("   <td colspan=\"2\" valign=\"top\"><img src=\"$_CMAPP[images_url]/pt-br/box_chat_agenda_amadis.gif\" width=\"431\" height=\"34\" border=\"0\"></td>");
    parent::add("   <td align=\"right\"><img src=\"$_CMAPP[images_url]/box_chat_agenda_amadis2.gif\"></td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("   <td width=\"15\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"15\" height=\"10\" border=\"0\"></td>");
    parent::add("   <td valign=\"top\">");
    parent::add("      <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">");
    parent::add("      <tr>");
    //inicio salas agendadas    
    if(!empty($this->agendados->items)){
      foreach($this->agendados as $futura){
	parent::add("  <tr>");
	parent::add("     <td  class=\"textoverde\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\"><br>");
	parent::add("       <b>$futura->nomSala</b> <br>$this->texto &nbsp;<font class=\"datachat\">".date("d/m/Y",$futura->datInicio)."</font><br>");
	parent::add("       por ".$futura->users[0]->name);
	parent::add("     </td>");
	parent::add("   </tr>");
      }
    }
    else{
      parent::add("<tr><td class=\"txtchat\"><b><br><br>$this->texto2</b></td></tr>");
    }
    //fim salas agendadas


    parent::add("      </tr>");
    parent::add("      </table>");
    parent::add("   </td>");
    parent::add("</table>");
    //parent::add("</td>");
    //parent::add("</tr>");
    //parent::add("</table>");
    





    
    return parent::__toString();
    
  }

}

?>