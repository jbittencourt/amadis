<?php
$sem_login = 1;
include_once("../../config.inc.php");

$ui = new RDui ("smartform","");
$lang = $_SESSION[environment]->getLangUI($ui);


//a função abaixo serve para tirar o offset em minutos
//entre o timezone o seu servidor e o horario GMT.
//Esse offset é diminuido do horário calculado de
//forma que o cliente manda um horário coerente 
//com o do servidor.
//
//retirado do help do PHP
function tzdelta ( $iTime = 0 )
{
   if ( 0 == $iTime ) { $iTime = time(); }
   $ar = localtime ( $iTime );
   $ar[5] += 1900; $ar[4]++;
 $iTztime = gmmktime ( $ar[2], $ar[1], $ar[0],
       $ar[4], $ar[3], $ar[5], $ar[8] );
   return ( $iTztime - $iTime );
}


?>

function date2timestamp(hour,min,second,month,day,year) {
  var offset = <? echo tzdelta(); ?>;

  var humDate = new Date(Date.UTC(year,
			 (stripLeadingZeroes(month)-1),
			 stripLeadingZeroes(day),
			 stripLeadingZeroes(hour),
			 stripLeadingZeroes(min),
			 stripLeadingZeroes(second)));

  return ((humDate.getTime()/1000.0)-offset);
}

function stripLeadingZeroes(input) {
  if((input.length > 1) && (input.substr(0,1) == "0"))
    return input.substr(1);
  else
    return input;
}


function makeUnixDate(hiddenform,unixdate) {
  hiddenform.value = unixdate;
}


function validateHour(hour_form) {
  if(hour_form.value>23) {
    alert('<? echo $lang[hora_invalida]; ?>');
    hour_form.focus();
    return 0;
  }
}

function validateSeconds(hour_form) {
  if(hour_form.value>60) {
    alert('<? echo $lang[segundos_invalida]; ?>');
    hour_form.focus();
    return 0;
  }
}

function validateMinutes(hour_form) {
  if(hour_form.value>60) {
    alert('<? echo $lang[minutos_invalida]; ?>');
    hour_form.focus();
    return 0;
  }
}



    date_updated_released_on     = false;
    date_updated_released_before = false;
    date_updated_released_since  = false;
    
    released_on_disabled     = false;
    released_before_disabled = false;
    released_since_disabled  = false;

    /**
    * Resets the above variables to false when form is cleared
    */
    function form_reset()
    {
        searchForm = document.forms['search_form'];

        if (1) {
            location.href = 'package-search.php';

        } else {
            date_updated_released_on     = false;
            date_updated_released_before = false;
            date_updated_released_since  = false;
    
            /**
            * Re-enable date dropdowns
            */
            searchForm.released_before_year.disabled  = false;
            searchForm.released_before_month.disabled = false;
            searchForm.released_before_day.disabled   = false;
    
            searchForm.released_since_year.disabled  = false;
            searchForm.released_since_month.disabled = false;
            searchForm.released_since_day.disabled   = false;
    
            searchForm.released_on_year.disabled  = false;
            searchForm.released_on_month.disabled = false;
            searchForm.released_on_day.disabled   = false;
    
            released_on_disabled     = false;
            released_before_disabled = false;
            released_since_disabled  = false;

            /**
            * Re-enable search button
            */
            searchForm.submitButton.disabled = false;
            return true;
        }
    }

    /**
    * When changed, the date fields in the forms are updated by this
    */
    function update_date(prefix, input)
    {
        searchForm = document.forms['search_form'];
        if (eval('date_updated_' + prefix)) return true;

        yearElement  = searchForm.elements[prefix + '_year'];
        monthElement = searchForm.elements[prefix + '_month'];
        dayElement   = searchForm.elements[prefix + '_day'];
        today = new Date();

        switch (input) {
            case 'year':
                if (monthElement.value != '' || dayElement.value != '') return true;
                monthElement.value = today.getMonth() + 1;
                dayElement.value = today.getDate();
                break;

            case 'month':
                if (yearElement.value != '' || dayElement.value != '') return true;
                yearElement.value = today.getFullYear();
                dayElement.value  = today.getDate();
                break;

            case 'day':
                if (yearElement.value != '' || monthElement.value != '') return true;
                yearElement.value  = today.getFullYear();
                monthElement.value = today.getMonth() + 1;
                break;
        }

        disableDateOptions(prefix);

        eval('date_updated_' + prefix + ' = true');
        return true;
    }

    /**
    * This function sets the date dropdowns to their
    * search values.
    */
    function setReleaseDropdowns()
    {
        if (0) {
            setDateFromCalendar_released_on('', '', '');
        } else {
            if (0) {
                setDateFromCalendar_released_before('', '', '');
            }
    
            if (0) {
                setDateFromCalendar_released_since('', '', '');
            }
        }
    }

    /**
    * Function to disable date dropdowns when the 
    * others are selected.
    */
    function disableDateOptions(prefix)
    {
        /**
        * Disable appropriate option based on what just changed.
        */
        searchForm = document.forms['search_form'];
        switch (prefix) {
            case 'released_on':
                searchForm.released_before_year.disabled  = true;
                searchForm.released_before_month.disabled = true;
                searchForm.released_before_day.disabled   = true;
                released_before_disabled = true;

                searchForm.released_since_year.disabled  = true;
                searchForm.released_since_month.disabled = true;
                searchForm.released_since_day.disabled   = true;
                released_since_disabled = true;
                break;

            case 'released_before':
            case 'released_since':
                searchForm.released_on_year.disabled  = true;
                searchForm.released_on_month.disabled = true;
                searchForm.released_on_day.disabled   = true;
                released_on_disabled = true;
                break;
        }
    }

    /**
    * Callback functions for the calendar
    */
    function setDateFromCalendar_released_on(date, month, year)
    {
        date_updated_released_on = true;
        return setDateFromCalendar('released_on', date, month, year);
    }

    function setDateFromCalendar_released_before(date, month, year)
    {
        date_updated_released_before = true;
        return setDateFromCalendar('released_before', date, month, year);
    }

    function setDateFromCalendar_released_since(date, month, year)
    {
        date_updated_released_since = true;
        return setDateFromCalendar('released_since', date, month, year);
    }

    function setDateFromCalendar(prefix, date, month, year)
    {
        searchForm = document.forms['search_form'];

        if (eval(prefix + '_disabled') == true) {
            return;
        } else {
            disableDateOptions(prefix);
        }

        yearElement  = searchForm.elements[prefix + '_year'].value = year;
        monthElement = searchForm.elements[prefix + '_month'].value = month;
        dayElement   = searchForm.elements[prefix + '_day'].value = date;
    }

    function validate_form()
    {
        searchForm = document.forms['search_form'];

        onYearElement  = searchForm.elements['released_on_year'];
        onMonthElement = searchForm.elements['released_on_month'];
        onDayElement   = searchForm.elements['released_on_day'];

        beforeYearElement  = searchForm.elements['released_before_year'];
        beforeMonthElement = searchForm.elements['released_before_month'];
        beforeDayElement   = searchForm.elements['released_before_day'];

        sinceYearElement  = searchForm.elements['released_since_year'];
        sinceMonthElement = searchForm.elements['released_since_month'];
        sinceDayElement   = searchForm.elements['released_since_day'];

        released_on_changed     = (onYearElement.value     != '' || onMonthElement.value     != '' || onDayElement.value     != '');
        released_before_changed = (beforeYearElement.value != '' || beforeMonthElement.value != '' || beforeDayElement.value != '');
        released_since_changed  = (sinceYearElement.value  != '' || sinceMonthElement.value  != '' || sinceDayElement.value  != '');

        if (released_on_changed && (released_since_changed || released_before_changed)) {
            alert('Cannot combine Released On and Released Before or Since!');
            return false;
        }
        
        document.forms['search_form'].submitButton.value    = 'Sending request...';
        document.forms['search_form'].submitButton.disabled = true;
    }

