<?php

/**
 * Show a calendar used in the blogs
 * This class requires PEAR::Calendar package
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
@require_once('Calendar/Month/Weekdays.php');

class AMTCalendar extends CMHTMLObj {

    protected $month;
    protected $year;
    protected $label;
    protected $weekday;
    protected $link_prev="#";
    protected $link_next="#";
    private $marked_days = array();

    public function __construct($m,$y) {
        global $_language;
        parent::__construct();

        parent::requires("calendar.css",CMHTMLPage::MEDIA_CSS);

        $this->month = (integer) $m;
        $this->year = (integer) $y;

        $this->label = array();
        $this->label[1] = $_language['january'];
        $this->label[2] = $_language['february'];
        $this->label[3] = $_language['march'];
        $this->label[4] = $_language['april'];
        $this->label[5] = $_language['may'];
        $this->label[6] = $_language['june'];
        $this->label[7] = $_language['july'];
        $this->label[8] = $_language['august'];
        $this->label[9] = $_language['september'];
        $this->label[10] = $_language['october'];
        $this->label[11] = $_language['november'];
        $this->label[12] = $_language['december'];

        $this->weekday = array();
        $this->weekday[1] = $_language['mon'];
        $this->weekday[2] = $_language['tue'];
        $this->weekday[3] = $_language['wed'];
        $this->weekday[4] = $_language['thu'];
        $this->weekday[5] = $_language['fri'];
        $this->weekday[6] = $_language['sat'];
        $this->weekday[7] = $_language['sun'];


    }
    
    public function setMoveLink($url) {

    //calcute the next an the previus month;
        if($this->month==12) {
            $nm = 1;
            $pm = 11;
            $ny = $this->year+1;
            $py = $this->year;

        }
        else {
            if($this->month==1) {
                $nm = 2;
                $pm = 12;
                $ny = $this->year;
                $py = $this->year-1;
            }
            else {
                $nm = $this->month+1;
                $pm = $this->month-1;
                $ny = $this->year;
                $py = $this->year;

            }
        }

        $this->link_next = $url."frm_calMonth=$nm&frm_calYear=$ny";
        $this->link_prev = $url."frm_calMonth=$pm&frm_calYear=$py";
    }

    public function pointDay($day,$link) {
        $this->marked_days[(integer)$day] = $link;
    }


    Public function __toString() {

        $Month = new Calendar_Month_Weekdays($this->year, $this->month); // October 2003
        $Month->build(); // Build the days in the month


        parent::add("<div id='amcalendar'>");
        parent::add("<div class='caption'>");
        parent::add("<a href='$this->link_prev' id='previous month' class='nav'>&laquo;</a> ".$this->label[(integer) $this->month]." ".$this->year);
        parent::add("<a href='$this->link_next' id='next month' class='nav'>&raquo;</a>");
        parent::add("</div>");

        parent::add("<div id='cal'>");

        $today_day = (integer) date("d",time());
        $today_month = (integer) date("m",time());
        $today_year = (integer) date("Y",time());


        foreach($this->weekday as $k=>$w) {
            if($k==1) {
                $id = "first_week";
            }
            else {
                $id = "bloco_week";
            }
            parent::add("<span id='$id'>".$w[0]."</span>");
        }

        while ($Day = $Month->fetch()) {
            $class = "";
            $text_day = (string)$Day->thisDay();
            $int_day = (integer)$Day->thisDay();
            if(array_key_exists($int_day,$this->marked_days)) {
                $temp = $this->marked_days[(integer)$Day->thisDay()];
                $text_day = "<a href='$temp'>$text_day</a>";
                $class = "marked_day";
            }

            if(($int_day==$today_day) && ($this->month==$today_month) && ($this->year==$today_year)) {
                $class .= " today";
            }
            
            if ($Day->isFirst()) {
                parent::add("<span class='firstday $class'>");
            }
            else {
                if(!empty($class)) $class = "class='$class'";
                parent::add("<span id='bloco' $class>");
            }

            if ($Day->isEmpty()) {
                parent::add("&nbsp;\n");
            } else {
                parent::add($text_day);
            }

            parent::add("</span>");
        }
        parent::add("</div>");
        parent::add("</div>");
        return parent::__toString();
    }

}
