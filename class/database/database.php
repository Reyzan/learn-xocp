<?php
//--------------------------------------------------------------------//
// Filename : class/database/database.php                             //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-09                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//


if(!defined("DATABASE_CONNECTION_INCLUDED")){
   define("DATABASE_CONNECTION_INCLUDED",TRUE);

class DBConnection {
   var $prefix;
   var $debug = false;

   function DBConnection(){
      die("Cannot instantiate this class directly");
   }

   function setPrefix($value) {
      $this->prefix = $value;
   }

   function prefix($tablename="") {
      if($tablename != ""){
         return $this->prefix . $tablename;
      } else {
         return $this->prefix;
      }
   }

   function setDebug($flag=true) {
      if ($flag) {
         $this->debug = true;
      } else {
         $this->debug = false;
      }
   }
}

} // DATABASE_CONNECTION_INCLUDED
?>