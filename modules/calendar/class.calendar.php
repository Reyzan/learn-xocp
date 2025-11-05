<?php
//
// By Ricardo Costa - ricardo@icorp.com.br - 2002
// Classe para exibiçao de calendário
//
//  calendar
//    +---- calendar()
//    +---- render()
//
//

class calendar {

   var $content;   // Conteudo HTML formatado
   var $page;   // Página para link
   var $month_name;   // Nome do mes
   var $year_bgcolor = "EEEEEE"; // Cor de fundo do ano
   var $month_bgcolor = "EEEEEE"; // Cor de fundo do mes
   var $days_bgcolor = "EEEEFF"; // Cor de fundo dos dias da semana
   var $day_color = "FFFFFF"; // Cor de fundo dos dias
   var $day_today_color = "DDDDFF"; // Cor de fundo de hoje
   var $font_color = "4C5B7D"; // Cor da fonte
   var $bg_color = "FFFFFF"; // Cor de fundo
   var $event_bgcolor = "FFCC99"; // Cor de fundo dos compromissos
   var $events = array(); // Array de eventos
   var $events_hint = array(); // Array com a descrição dos eventos


   function calendar() {
      global $calConfig;

      $this->page = $GLOBALS["PHP_SELF"];
      
      if (isset($calConfig["nyear"])) {
         $calConfig["year"] = $calConfig["nyear"];
      } else {
         $calConfig["nyear"] = $calConfig["year"];
      }
      
      if (isset($calConfig["nmonth"])) {
         $calConfig["month"] = $calConfig["nmonth"];
      } else {
         $calConfig["nmonth"] = $calConfig["month"];
      }
      
      if (isset($calConfig["nday"])) {
         $calConfig["day"] = $calConfig["nday"];
      } else {
         $calConfig["nday"] = $calConfig["day"];
      }

      if ($calConfig["nmonth"] == 0) {
         $calConfig["nyear"] --;
         $calConfig["nmonth"] = 12;
      }
      elseif ($calConfig["nmonth"] == 13) {
         $calConfig["nyear"] ++;
         $calConfig["nmonth"] = 1;
      }

      $this->month_name = $calConfig["month_year"];
      $this->month_name = $this->month_name[$calConfig["nmonth"]];
   }


   function render($year = 1, $month = 1, $today = 1) {
      global $calConfig;
      $today_str = $calConfig['today_str'];
      $days_week = $calConfig['days_week'];

      $this->content = "
      <style type='text/css'>
      <!--
      .calendar_font {  font-weight: normal; font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #".$this->font_color."; text-decoration: none}
      .calendar_font:hover {  font-weight: normal; font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #".$this->font_color."; text-decoration: underline overline; background-color:#ddddff}
      -->
      </style>
      <table width='140' border='0' cellspacing='0' cellpadding='1' class='calendar_font'>";

      if ($year == 1) {
         $this->content .= "<tr align='center'><td width='20' bgcolor='#".$this->year_bgcolor
         ."' height='14'><b><a href='".$this->page."?nmonth=".$calConfig["nmonth"]."&nyear="
         .($calConfig["nyear"] - 1)."&nday=".$calConfig["nday"]
         ."' class='calendar_font'>&#139;&#139;&#139;</a></b></td><td colspan='5' bgcolor='#"
         .$this->year_bgcolor."' height='14'><b>".$calConfig["nyear"]
         ."</b></td><td width='20' bgcolor='#".$this->year_bgcolor."' height='14'><b><a href='"
         .$this->page."?nmonth=".$calConfig["nmonth"]."&nyear=".($calConfig["nyear"] + 1)
         ."&nday=".$calConfig["nday"]."' class='calendar_font'>&#155;&#155;&#155;</a></b></td></tr>";
      } 
    
      if ($month == 1) {
         $this->content .= "<tr align='center'><td width='20' bgcolor='#".$this->month_bgcolor
         ."' height='18'><b><a href='".$this->page."?nmonth=".($calConfig["nmonth"] - 1)
         ."&nyear=".$calConfig["nyear"]."&nday=".$calConfig["nday"]
         ."' class='calendar_font'>&#139;&#139;</a></b></td><td colspan='5' bgcolor='#"
         .$this->month_bgcolor."' height='18'><b>".$this->month_name
         ."</b></td><td width='20' bgcolor='#".$this->month_bgcolor."' height='18'><b><a href='"
         .$this->page."?nmonth=".($calConfig["nmonth"] + 1)."&nyear=".$calConfig["nyear"]
         ."&nday=".$calConfig["nday"]."' class='calendar_font'>&#155;&#155;</a></b></td>"
         ."</tr><tr align='center'><td bgcolor='#000000' height='1'></td>"
         ."<td colspan='5' bgcolor='#000000' height='1'></td>"
         ."<td bgcolor='#000000' height='1'></td></tr>";
      }

 
      $this->content .= "<tr align='center' bgcolor='#".$this->days_bgcolor."'>";
      for ($l = 0; $l <=6; $l++) {
         $this->content .= "<td width='20' height='14'>".$days_week[$l][0]."</td>";
      }
      $this->content .= "</tr>";


      $cont_day = 1;
      $event_number = 0;
      for( $l = 1; $l <= 6; $l++) {

         $this->content .= "<tr>";

         for($c = 0; $c <= 6 ; $c++) {
            $xday = date("w",mktime (0,0,0, $calConfig["nmonth"],$cont_day, $calConfig["nyear"]));
 
            if (in_array($calConfig["nyear"]."-"
                .$calConfig["nmonth"]."-"
                .mformat(2 ,$cont_day), $this->events)) {
               $bg = "bgcolor='#".$this->event_bgcolor."'";
               if ($cont_day != $calConfig["nday"]) {
                  $p_day = $cont_day;
               } else {
                  $p_day = "<b><font color=#0000ff>$cont_day</font></b>";
               }
            } else { 
               if ($cont_day != $calConfig["nday"]) {
                  $bg = "bgcolor='#".$this->day_color."'";
                  $p_day = $cont_day;
               } else {
                  $bg = "bgcolor='".$this->bg_color."'";
                  $p_day = "<b><font color=#0000ff>$cont_day</font></b>";
               }
            }
         
            if (checkdate($calConfig["nmonth"], $cont_day, $calConfig["nyear"]) & $xday == $c) {
               if (in_array($calConfig["nyear"]."-".$calConfig["nmonth"]."-".mformat(2 ,$cont_day), $this->events)) {
                  $this->content .= "\n<td align='center' width='20' "
                                    .$bg."><a href='".$this->page."?nmonth=".$calConfig["nmonth"]
                                    ."&nyear=".$calConfig["nyear"]."&nday=".$cont_day
                                    ."' class='calendar_font' title='"
                                    .$this->events_hint[$event_number++]."'>".$p_day."</a></td>";
               } else { 
                  $this->content .= "\n<td align='center' width='20' "
                                    .$bg."><a href='".$this->page."?nmonth="
                                    .$calConfig["nmonth"]."&nyear=".$calConfig["nyear"]
                                    ."&nday=".$cont_day."' class='calendar_font'>"
                                    .$p_day."</a></td>";
               }
               $cont_day++;
            } else {
               $this->content .= "\n<td align='center' width='20' bgcolor='#"
                                 .$this->bg_color."'>&nbsp;</td>";
            }
         }
    
         $this->content .= "</tr>";
         if (!checkdate($calConfig["nmonth"], $cont_day, $calConfig["nyear"])) {
            break;
         }
      }
   
      if ($today == 1) {
         $this->content .= "<tr><td colspan=7 align='center' bgcolor=#".$this->year_bgcolor."><b><a href='".$this->page
         ."?nmonth=".date("n")."&nyear=".date("Y")."&nday=".date("d")."' class='calendar_font'>"
         ."$today_str</a></b></td></tr>";
      }
     
      $this->content .= "</table>";
     
      return ($this->content);
   }



} 
?>