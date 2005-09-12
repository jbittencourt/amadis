<?

class TranscriptDB {

  public function Starting($bdhost, $bduser,$bdpass,$bdname) {
    require('safe_mysql.php');
    require('constants.php');
    try {
       $mysqli = new safe_mysqli($bdhost, $bduser, $bdpass, $bdname);
       $newsqli = new safe_mysqli($bdhost, $bduser, $bdpass);
       if ($newres = $newsqli->query("CREATE DATABASE `amadis` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci")) {
//       foreach ($tabsArray as $tab) {
         $tab = "areas";
         $thenew = $this->takeoff (${"cr_$tab"});
	     $inserts = $this->getAndCreate($tab,$thenew,${"flds_$tab"},$mysqli);
         echo("\nTabela ".$tab."<BR>".${"cr_$tab"}."<br><br>".$inserts);
//       }
//       foreach ($newsArray as $news) {
//         echo("\nTabela ".$news."<BR>".${"cr_$news"}."<br>");
//       }
       }
       $mysqli->close();
       $newsqli->close();

    } catch (SQLException $e) {
      die("SQL Error : ".$e->getSQLError()." in <hr><PRE>".$e->getSQL()."</PRE><hr>");
    } catch (DBException $e) {
      die("Database Error: ". $e->GetMessage());
    } catch (Exception $e) {
      die(exception_dump($e));
    }
  }

  private function getAndCreate ($table,$newtab,$whatfields,$mymysql) {
    if ($res = $mymysql->query("SELECT * FROM ".$table)) {
      $f_info = $res->fetch_fields();
      foreach ($whatfields as $name) {
        $i = -1;
        do {
          $i++;
          $tmp = $f_info[$i];
        } while ($tmp->name != $name);
        $info[$tmp->name] = $tmp;
      }
      $lines = "";
      while ($row = $res->fetch_object()) {
        $lines = $lines."\n\nINSERT INTO `".$newtab."` VALUES (";
        foreach ($whatfields as $field) {
          $value = $this->specialTables($table,$field,$row);
          if ($value != 'false') {
              $lines = $lines.$value;
          }
          else {
            $t = $info[$field]->type;
            if ($t == 252 or $t == 253 or $t == 254) {
              $lines = $lines."'".$row->$field."',";
            }
            else {
              $lines = $lines.$row->$field.",";
            }
          }
        }
        $lines{strlen($lines)-1} = '';
        $lines = $lines.");\n<br>";
      }
      return $lines;
    }
    $res->close();
  }

  private function specialTables ($spctable,$spcfield,$spcrow) {
    $spcline = "";
    if ($spctable == 'Finder_Mensagens' and $spcfield == 'flaLida') {
      if ($spcrow->$spcfield == '1') {
        $spcline = "'READ',";
      }
      else {
        $spcline = "'NOT_READ',";
      }
    }
    else if ($spctable == 'diario' and $spcfield == 'codTexto') {
      $spcline = $spcrow->$spcfield.",1,'???vazio???',";
    }
    else if ($spctable == 'noticias' and $spcfield == 'codNoticia') {
      $spcline = $spcrow->$spcfield.",1,'???vazio???',";
    }
    else if ($spctable == 'projeto' and $spcfield == 'flaEstado') {
      $spcline = $spcrow->$spcfield.",1,";
    }
    else if ($spctable == 'projetoMatricula' and $spcfield == 'codProjeto') {
      $spcline = $spcrow->$spcfield.",'INVITATION','ACCEPTED',";
    }
    else if ($spctable == 'user' and $spcfield == 'desHistorico') {
      $spcline = $spcrow->$spcfield.",1,";
    }
    else {
      $spcline = 'false';
    }
    return $spcline;
  }

  private function takeoff ($whole) { // gets "CREATE TABLE `<table>` ( ..." and taakes off <table>
    $pos = strpos($whole, '(');
    $newname = substr($whole, 14, $pos-16);
    return $newname;
  }
  
/**
  public function numTypeToName ($num) {  //return the variable types for a code from ->type
    switch ($num) {
      case 0: return "decimal"; break;
      case 1: return "tinyint"; break;
      case 2: return "smallint"; break;
      case 3: return "int"; break;
      case 4: return "float"; break;
      case 5: return "double"; break;
      case 7: return "timestamp"; break;
      case 8: return "bigint"; break;
      case 9: return "mediumint"; break;
      case 10: return "date"; break;
      case 11: return "time"; break;
      case 12: return "datetime"; break;
      case 13: return "year"; break;
      case 252: return "blob"; break; // text, blob, tinyblob,mediumblob, etc...
      case 253: return "string"; break; // varchar and char
      case 254: return "enum";
    }
  }
**/

}

?>
