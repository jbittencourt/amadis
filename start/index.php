<html>
<head>
<title>Instalacao do AMADIS</title>
</head>

<body>
<H3>AMADIS - Ambiente de Aprendizagem a Distancia</H3><BR>
<?
//conferir se jah existe uma versao do amadis instalada

//conferir versao do MySql
$ver = get_client_version();
if ($ver >= 40110) {
  print "Versao do MySql =  ".$this-> mysqlClientVersion($ver);
  print "&nbsp;&nbsp;<font color='#009900'><b>ok</b></font><br>";
}
else {
  $this->CallErrorPage("mysql_old");
}

?>
Jah existe uma versao do AMADIS instalada no sistema?<BR>
<FORM NAME='loginbd' METHOD='post' ACTION="mudabd/install.php">
   <INPUT NAME='have' TYPE='radio' VALUE='sim'>&nbsp;Sim&nbsp;&nbsp;&nbsp;
   <INPUT NAME='have' TYPE='radio' VALUE='no'>Nao<BR><BR>
Dados da conexao com o banco de dados (para isso voce precisa
possuir permissoes de administrador do banco de dados)<BR>
  usuario:  <INPUT NAME="user" TYPE="text"><BR>
  &nbsp;&nbsp; senha:  <INPUT NAME="passw" TYPE="password"><BR>
  <BR>Digite o nome do banco de dados em que estah instalado o amadis:
  <INPUT NAME="dbank" TYPE="text"><BR><BR>
  <INPUT NAME="subm" TYPE="submit" VALUE="Enviar">
</FORM>
</body> </html>
<?
  function mysqlClientVersion ($version) {
    $strversion = strval($version);
    $v1 = substr($strversion, 1, 2);
    if (substr($v1,0,0) == "0") {
      $v1 = substr($v1,1,1);
    }
    else {
      $v1 = substr($v1,0,1);
    }

    $v2 = substr($strversion, 3, 4);
    if (substr($v2,0,0) == "0") {
      $v2 = substr($v2,1,1);
    }
    else {
      $v2 = substr($v2,0,1);
    }

    $strver = substr($myversion, 0, 0).".".$v1.".".$v2;
    return $strver;
  }
  
  function CallErrorPage () {
  }

?>