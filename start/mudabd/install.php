<?

require('TranscriptDB.php');

class Instalation extends TranscriptDB {
  
  public function __construct() {
    $rqarray = array();
    foreach($_REQUEST as $item=>$value){
      $rqarray[$item] = $value;
    }
    //copia dos arquivos
    if ($have == 'sim') {
      $this->export($rqarray);
      parent::StartConverting($rqarray,true);
    }
    else {
      parent::StartConverting($rqarray,false);
    }
  }


  public function export($reqarray){    

    require('safe_mysql.php');
    
    $dbank = $reqarray['dbank'];
    $host = 'localhost';
    $user = $reqarray['user'];
    $pass = $reqarray['passw'];
    echo("Exportando banco de dados '".$dbank."' para 'amadis_TEMP'<BR>");
    if ($dbc = new safe_mysqli($host, $user, $pass)) {
      $file = $dbank."_backup";
      $command = "mysqldump --host=".$host." --user=".$user." --password=".$pass." ".$dbank." > ".$file.".sql";
      $ans = shell_exec($command);
      //$query = "CREATE DATABASE `amadis_TEMP` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci";
      //if ($answ = $dbc->query($query)) {
        $command = "mysql --host=".$host." --user=".$user." --password=".$pass." amadis_TEMP < ".$file.".sql";
        $ans = shell_exec($command);      
        $ans = shell_exec("rm ".$file.".sql");
      //}
      $dbc->close();
    }
  }

}

$parara = new Instalation();

?>

