<?php
//--------------------------------------------------------------------//
// Filename : modules/calendar/calendar.php                           //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-13                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('CALENDAR_DEFINED') ) {
   define('CALENDAR_DEFINED', TRUE);

global $nyear,$nmonth,$nday,$calConfig;
include_once(XOCP_DOC_ROOT."/modules/calendar/class.calendar.php");

$calConfig['nyear'] = $nyear;
$calConfig['nmonth'] = $nmonth;
$calConfig['nday'] = $nday;

$calConfig['month_year'] = array("",_CL_JAN, _CL_FEB, _CL_MAR, _CL_APR, _CL_MAY, _CL_JUN, _CL_JUL, _CL_AUG, _CL_SEP, _CL_OCT, _CL_NOV, _CL_DEC);
$calConfig['days_week'] = array(_CL_SUN, _CL_MON, _CL_TUE, _CL_WED, _CL_THU, _CL_FRI, _CL_SAT);
$calConfig['today_str'] = _CL_TODAY;

$arr_day = getdate();

$calConfig['sec'] = $arr_day["minutes"];
$calConfig['mi'] = $arr_day["seconds"];
$calConfig['hour'] = $arr_day["hours"];
$calConfig['day'] = $arr_day["mday"];
$calConfig['day_week'] = $arr_day["wday"];
$calConfig['day_week_ext'] = $days_week[$arr_day["wday"]];
$calConfig['month'] = $arr_day["mon"];
$calConfig['month_ext'] = $month_year[$month];
$calConfig['year'] = $arr_day["year"];

function mformat($zeros,$num) {
   for($i = 1; $i <= $zeros - strlen($num); $i++) {
      $num = "0".$num;
   }
   return $num;
}

function mno_zero($num) {
   if (substr($num,0,1) == "0") {
      return substr($num,1,1);
   } else {
      return $num;
   }
}

function mdia_semana($formato,$data) {
   global $calConfig;
   $d = date("w",mktime (0,0,0,mno_zero(substr($data,5,2)),mno_zero(substr($data,8,2)),substr($data,0,4)));
   $arr_d = $calConfig["days_week"];
   if ($formato == "t") {
      return $arr_d[$d];
   } else {
      return $d-1;
   }
}


function mdata_atual($sep) {
   global $calConfig;
   return $calConfig['year'].$sep.mformat(2,$calConfig['month']).mformat(2,$sep.$calConfig['day']);
}

function mdata_ext($data) {
   global $calConfig;
   $arr_m = $calConfig['month_year'];
   return mdia_semana("t",$data).", ".substr($data,8,2)." de ".$arr_m[date("n",mktime (0,0,0,mno_zero(substr($data,5,2)),mno_zero(substr($data,8,2)),substr($data,0,4)))]." de ".substr($data,0,4);
}

function mdata_br($data,$sep) {
   if($data != "0000-00-00" && $data != "0000-00-00 00:00:00" && $data != "") {
      return substr($data,8,2).$sep.substr($data,5,2).$sep.substr($data,0,4);
   }
}

function mdata_mysql($data) {
   if($data != "00/00/0000" && $data != "") {
      return substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
   }
}


class _calendar_small extends XocpBlock {

   function main() {
      $c = new calendar();
      $c->events = Array("2002-11-23","2002-11-27", "2003-1-15");
      $c->events_hint = Array("Gue cuti, mau ke Ngawi","Workshop DHS di Jakarta","");
      krsort($c->events_hint);

      $ret = $c->render(1, 1, 1);

      return $ret;
   }

}

} // CALENDAR_DEFINED
?>