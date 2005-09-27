<?

class AMBStatistics extends AMBox {
  private $stats;
  
  public function __construct() {
    parent::__construct("stats",AMBox::COLOR_WHITE);
    $this->stats = $_SESSION[environment]->getStats();

    $this->requires("stats.css",CMHTMLObj::MEDIA_CSS);
  }

  public function __toString() {
    global $_CMAPP,$_language;
    $stats = $this->stats;

    parent::add('<table id="stats_table">');
    parent::add('<tr>');
    parent::add('<td id="stats_col">');
    
    $max = max($stats[communities],$stats[people],$stats[projects],$stats[communities]);
    if($max > 0) {
      $p_communities = ($stats[communities]/$max)*50;
      $p_people = ($stats[people]/$max)*50;
      //$p_courses = ($stats[courses]/$max)*50;
      $p_projects = ($stats[projects]/$max)*50;
    }
    $dot = '<img width="1" height="1" border="0" src="'.$_CMAPP[images_url].'/dot.gif">';
    parent::add('<span id="bar_communities" style="height: '.$p_communities.'%;" class="stats">'.$dot.'</span>');
    parent::add('<span id="bar_people" style="height: '.$p_people.'%;" class="stats">'.$dot.'</span>');
    parent::add('<span id="bar_projects" style="height: '.$p_projects.'%;" class="stats">'.$dot.'</span>');
    //parent::add('<span id="bar_courses" style="height: '.$p_courses.'%;" class="stats">'.$dot.'</span>');

    parent::add('</td>');
    parent::add('<td><img width="25" height="1" border="0" src="'.$_CMAPP[images_url].'/dot.gif"></td>');
    parent::add('<td>');

    parent::add("<span id=\"stats_communities\" class=\"stats_text\"> $stats[communities] $_language[communities]</span>");
    parent::add("<br><span id=\"stats_people\" class=\"stats_text\"> $stats[people] $_language[people]</span>");
    parent::add("<br><span id=\"stats_projects\" class=\"stats_text\"> $stats[projects] $_language[projects]</span>");
    //    parent::add("<br><span id=\"stats_courses\" class=\"stats_text\"> $stats[courses] $_language[courses]</span>");

    parent::add('</td>');


    parent::add('<tr><td colspan=3>');
    parent::add('<img id="logo_amadis" src="'.$_CMAPP[images_url].'/box_statusamadis_amadis.gif">');
    
    parent::add('</table>');

    return parent::__toString();
  }

}



?>