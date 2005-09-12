<?


class AMUploadBox extends RDPagObj {
    
  function AMUploadBox($diretorioAtual="") {
    global $urlimagens,$urlimlang;

    $script = "<SCRIPT language=\"Javascript\" type=\"text/javascript\">";
    $script.= "  function go_to_copia() {\n";
    $script.= "    var location = \"".$_SERVER[PHP_SELF]."?acao=A_copia\";";
    $script.= "    if (selecionados.length > 0) {\n";
    $script.= "      for (var i=0; i<selecionados.length;i++) {\n";
    $script.= "        if (selecionados[i]!=\"\") {\n";
    $script.= "          location += \"&arquivos[]=\" + selecionados[i];\n";
    $script.= "        }\n";
    $script.= "      }\n"; 
    $script.= "      window.location.href = location;\n"; 
    $script.= "    }\n";
    $script.= "  }\n";   

    $script.= "  function go_to_apaga() {\n";
    $script.= "    var location = \"".$_SERVER[PHP_SELF]."?acao=A_apaga\";";
    $script.= "    if (selecionados.length > 0) {\n";   
    $script.= "      for (var i=0; i<selecionados.length;i++) {\n";
    $script.= "        if (selecionados[i]!=\"\") {\n";
    $script.= "          location += \"&arquivos[]=\" + selecionados[i];\n";
    $script.= "        }\n";
    $script.= "      }\n"; 
    $script.= "      window.location.href = location;\n"; 
    $script.= "    }\n";
    $script.= "  }\n";

    $script.= "  function go_to_download(zip,tar) {\n";
    $script.= "    var location = \"".$_SERVER[PHP_SELF]."?acao=A_download\";";
    $script.= "    if (zip) {\n";
    $script.= "      location += \"&flagZip=1\";";
    $script.= "    }\n";
    $script.= "    if (tar) {\n";
    $script.= "      location += \"&flagTar=1\";";
    $script.= "    }\n";   
    $script.= "    if (selecionados.length > 0) {\n";
    $script.= "      for (var i=0; i<selecionados.length;i++) {\n";
    $script.= "        if (selecionados[i]!=\"\") {\n";
    $script.= "          location += \"&arquivos[]=\" + selecionados[i];\n";
    $script.= "        }\n";
    $script.= "      }\n"; 
    $script.= "      window.location.href = location;\n"; 
    $script.= "    }\n";
    $script.= "  }\n";

    if ($_REQUEST[refreshTreeFrame]) {
      //faz um refresh no frame da esquerda
      $script.= "\n\n";
      $script.= "parent.fr_arvore.location.reload();\n";
    }
    $script.= "</SCRIPT>";
    $this->add($script);
    
    $this->add("<table background=\"$urlimagens/bg_cinza.gif\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" align=center>");
    $this->add("<tr>");
    $this->add("<td rowspan=\"4\"><td><img src=\"$urlimagens/dot.gif\" width=\"3\" height=\"1\" border=\"0\"></td>");
    $icon = new WIcon("Home",$urlimagens.$tema[home],$_SERVER[PHP_SELF]."?acao=A_diretorio");
    
    //icones
    $this->add("<td>");
    $this->add("<a href=\"javascript:go_to_copia()\"><img src=\"$urlimlang/ico_amadis_baby_copiar.gif\" border=\"0\" width=\"57\" height=\"57\" vspace=\"5\"></a>");
    $this->add("<a href=\"$_SERVER[PHP_SELF]?acao=A_cola\"><img src=\"$urlimlang/ico_upload_colar.gif\" border=\"0\" width=\"57\" height=\"57\" vspace=\"5\"></a>");
    $this->add("<a href=\"javascript:go_to_apaga()\"><img src=\"$urlimlang/ico_upload_excluir.gif\" border=\"0\" width=\"57\" height=\"57\" vspace=\"5\"></a>");
    $this->add("<a href=\"$_SERVER[PHP_SELF]?acao=A_cria_dir\"><img src=\"$urlimlang/ico_upload_nova_pasta.gif\" border=\"0\" width=\"57\" height=\"57\" vspace=\"5\"></a>");
    $this->add("<img src=\"$urlimagens/dot.gif\" width=\"57\" height=\"57\" vspace=\"5\">");

    //monta o diretorio pai
    if(!empty($diretorioAtual)) {
      $pos = strrpos($diretorioAtual,"/");
      if ($pos==0)
	$dir_pai = "";
      else
	$dir_pai = substr($diretorioAtual,0,$pos);
      $this->add("<a href=\"$_SERVER[PHP_SELF]?acao=A_diretorio&diretorio=".$dir_pai."\"><img src=\"$urlimlang/ico_upload_acima.gif\" border=\"0\" width=\"57\" height=\"57\" vspace=\"5\"></a>");
      $this->add("</td>");
    }

    //icones enviar e baixar
    $this->add("<td rowspan=\"4\"><img src=\"$urlimagens/dot.gif\" width=\"3\" height=\"1\" border=\"0\"></td>");
    $this->add("<td valign=\"top\" rowspan=\"4\" bgcolor=\"#ffffff\" align=\"center\" width=\"60\">");
    $this->add("<br><br><br><br><br><br><a href=\"javascript:go_to_download()\"><img src=\"$urlimlang/btn_upload_baixar.gif\" width=\"38\" height=\"43\" border=\"0\"></a>");
    $this->add("<br><br><a href=\"$_SERVER[PHP_SELF]?acao=A_envia_arq\"><img src=\"$urlimlang/btn_upload_enviar.gif\" width=\"38\" height=\"43\" border=\"0\"></a>");
    $this->add("</td>");
    $this->add("<td valign=\"top\" rowspan=\"4\" bgcolor=\"#ffffff\"><br><br><br><br><img src=\"$urlimagens/img_computador.gif\" width=\"108\" height=\"147\" border=\"0\"></td>");
    $this->add("</tr>");
    
    $this->add("<tr>");
    $this->add("<td><img src=\"$urlimagens/dot.gif\" width=\"3\" height=\"1\" border=\"0\"></td>");
    if (empty($diretorioAtual))
      $diretorioAtual = "Home";
          
    $this->add("<td bgcolor=\"#F1F1F0\" height=\"25\" style=\"padding-left: 10px\"><font class=\"fontgray\">".$diretorioAtual."</font></td>");
    $this->add("</tr>");
    $this->add("<tr>");
    $this->add("<td><img src=\"$urlimagens/dot.gif\" width=\"1\" height=\"4\" border=\"0\"></td>");
    $this->add("<td>");
    
  }

  function imprime() {
    $this->add("</td></tr>");
    $this->add("</table>");
    parent::imprime();
  }
  
}



?>